<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    protected $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');

        // Ensure backup directory exists
        if (!File::isDirectory($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Display the backup management page.
     */
    public function index()
    {
        $backups = [];
        $files = File::files($this->backupPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_human' => $this->humanFileSize($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        // Sort by newest first
        usort($backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return view('admin.backup', compact('backups'));
    }

    /**
     * Create a new backup and download it.
     */
    public function create()
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $destination = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $mysqldumpPaths = [
            'mysqldump', // if in PATH
            'E:\laragon\bin\mysql\mysql-8.0.45-winx64\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe'
        ];

        $success = false;
        foreach ($mysqldumpPaths as $bin) {
            $passArg = $dbPass ? "-p\"{$dbPass}\"" : "";
            $cmd = "\"{$bin}\" -u \"{$dbUser}\" {$passArg} \"{$dbName}\" > \"{$destination}\" 2>&1";
            exec($cmd, $output, $returnVar);
            if ($returnVar === 0) {
                $success = true;
                break;
            }
        }

        if (!$success) {
            return redirect('/admin/backup')->with('error', __('Lỗi không thể tạo bản sao lưu MySQL. Vui lòng kiểm tra mysqldump.'));
        }

        return response()->download($destination, $filename)->deleteFileAfterSend(false);
    }

    /**
     * Restore from an existing backup on the server.
     */
    public function restore(Request $request)
    {
        $request->validate(['filename' => 'required|string']);

        $filename = basename($request->input('filename')); // prevent directory traversal
        $backupFile = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($backupFile)) {
            return redirect('/admin/backup')->with('error', __('Bản sao lưu không tồn tại.'));
        }

        // --- Execute restore via mysql ---
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $mysqlPaths = [
            'mysql',
            'E:\laragon\bin\mysql\mysql-8.0.45-winx64\bin\mysql.exe',
            'C:\xampp\mysql\bin\mysql.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe'
        ];

        $success = false;
        foreach ($mysqlPaths as $bin) {
            $passArg = $dbPass ? "-p\"{$dbPass}\"" : "";
            $cmd = "\"{$bin}\" -u \"{$dbUser}\" {$passArg} \"{$dbName}\" < \"{$backupFile}\" 2>&1";
            exec($cmd, $output, $returnVar);
            if ($returnVar === 0) {
                $success = true;
                break;
            }
        }

        if (!$success) {
            return redirect('/admin/backup')->with('error', __('Lỗi không thể khôi phục MySQL. Vui lòng kiểm tra lệnh mysql.'));
        }

        return redirect('/admin/backup')->with('success', 'Đã khôi phục thành công từ bản sao lưu "' . $filename . '".');
    }

    /**
     * Upload a .sql file and restore from it.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400', // Max 100MB
        ]);

        $file = $request->file('backup_file');

        if ($file->getClientOriginalExtension() !== 'sql') {
            return redirect('/admin/backup')->with('error', __('Chỉ chấp nhận file có đuôi .sql'));
        }

        $filename = 'uploaded_' . date('Y-m-d_H-i-s') . '.sql';
        $backupFile = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
        $file->move($this->backupPath, $filename);

        // --- Execute restore via mysql ---
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $mysqlPaths = [
            'mysql',
            'E:\laragon\bin\mysql\mysql-8.0.45-winx64\bin\mysql.exe',
            'C:\xampp\mysql\bin\mysql.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe'
        ];

        $success = false;
        foreach ($mysqlPaths as $bin) {
            $passArg = $dbPass ? "-p\"{$dbPass}\"" : "";
            $cmd = "\"{$bin}\" -u \"{$dbUser}\" {$passArg} \"{$dbName}\" < \"{$backupFile}\" 2>&1";
            exec($cmd, $output, $returnVar);
            if ($returnVar === 0) {
                $success = true;
                break;
            }
        }

        if (!$success) {
            return redirect('/admin/backup')->with('error', __('Lỗi không thể khôi phục MySQL từ file upload.'));
        }

        return redirect('/admin/backup')->with('success', __('Đã tải lên và khôi phục thành công từ file upload.'));
    }

    /**
     * Download a specific backup file.
     */
    public function download($filename)
    {
        $filename = basename($filename);
        $filePath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($filePath)) {
            return redirect('/admin/backup')->with('error', __('File không tồn tại.'));
        }

        return response()->download($filePath, $filename);
    }

    /**
     * Delete a backup file.
     */
    public function destroy($filename)
    {
        $filename = basename($filename);
        $filePath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect('/admin/backup')->with('success', 'Đã xoá bản sao lưu "' . $filename . '".');
        }

        return redirect('/admin/backup')->with('error', __('File không tồn tại.'));
    }

    /**
     * Convert bytes to human-readable size.
     */
    private function humanFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
