<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckInventoryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check inventory for low stock and expiring ingredients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = \App\Models\User::join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('roles.code', ['admin', 'staff'])
            ->select('users.id')
            ->get();

        if ($admins->isEmpty()) {
            return;
        }

        $ingredients = \App\Models\Ingredient::with('unit')->get();

        foreach ($ingredients as $ingredient) {
            // 1. Check Low Stock
            if ($ingredient->current_stock <= $ingredient->minimum_stock) {
                $this->notifyAdmins($admins, 
                    'Nguyên liệu sắp hết: ' . $ingredient->name, 
                    "Kho hiện tại chỉ còn {$ingredient->current_stock} {$ingredient->unit->name}, bằng hoặc dưới mức tối thiểu ({$ingredient->minimum_stock}). Vui lòng nhập thêm.",
                    'low_stock'
                );
            }

            // 2. Check Expiration
            if ($ingredient->expiration_date) {
                if ($ingredient->is_fresh) {
                    $hoursLeft = now()->diffInHours($ingredient->expiration_date, false);
                    if ($hoursLeft >= 0 && $hoursLeft <= 2) {
                        $this->notifyAdmins($admins,
                            'Đồ tươi sắp hỏng: ' . $ingredient->name,
                            "Nguyên liệu này sẽ hết hạn vào " . $ingredient->expiration_date->format('H:i d/m/Y') . " (trong vòng 2 tiếng nữa).",
                            'expiring_soon'
                        );
                    }
                } else {
                    $daysLeft = now()->diffInDays($ingredient->expiration_date, false);
                    if ($daysLeft >= 0 && $daysLeft <= 2) {
                        $this->notifyAdmins($admins,
                            'Nguyên liệu sắp hết hạn: ' . $ingredient->name,
                            "Nguyên liệu này sẽ hết hạn vào " . $ingredient->expiration_date->format('d/m/Y') . " (trong vòng 2 ngày tới).",
                            'expiring_soon'
                        );
                    }
                }
            }
        }
        
        $this->info('Inventory check completed.');
    }

    private function notifyAdmins($admins, $title, $content, $type)
    {
        foreach ($admins as $admin) {
            // Avoid duplicate notifications on the same day for the same content
            $exists = \App\Models\Notification::where('user_id', $admin->id)
                ->where('title', $title)
                ->whereDate('created_at', now()->toDateString())
                ->exists();
                
            if (!$exists) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'content' => $content,
                    'type' => $type,
                    'is_read' => false,
                ]);
            }
        }
    }
}
