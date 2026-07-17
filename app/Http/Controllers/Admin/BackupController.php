<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    protected $backupPath;
    protected $dbPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->dbPath = database_path('database.sqlite');

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
            if ($file->getExtension() === 'sqlite') {
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
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sqlite';
        $destination = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($this->dbPath)) {
            return redirect('/admin/backup')->with('error', 'Không tìm thấy file cơ sở dữ liệu.');
        }

        File::copy($this->dbPath, $destination);

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
            return redirect('/admin/backup')->with('error', 'Bản sao lưu không tồn tại.');
        }

        // Auto-backup current DB before restoring
        $autoBackupName = 'auto_before_restore_' . date('Y-m-d_H-i-s') . '.sqlite';
        File::copy($this->dbPath, $this->backupPath . DIRECTORY_SEPARATOR . $autoBackupName);

        // Replace current database
        File::copy($backupFile, $this->dbPath);

        return redirect('/admin/backup')->with('success', 'Đã khôi phục thành công từ bản sao lưu "' . $filename . '". Bản sao lưu tự động trước khi khôi phục: "' . $autoBackupName . '".');
    }

    /**
     * Upload a .sqlite file and restore from it.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400', // Max 100MB
        ]);

        $file = $request->file('backup_file');

        if ($file->getClientOriginalExtension() !== 'sqlite') {
            return redirect('/admin/backup')->with('error', 'Chỉ chấp nhận file có đuôi .sqlite');
        }

        // Auto-backup current DB before restoring
        $autoBackupName = 'auto_before_upload_restore_' . date('Y-m-d_H-i-s') . '.sqlite';
        File::copy($this->dbPath, $this->backupPath . DIRECTORY_SEPARATOR . $autoBackupName);

        // Save uploaded file and replace current database
        $file->move(dirname($this->dbPath), basename($this->dbPath));

        return redirect('/admin/backup')->with('success', 'Đã khôi phục thành công từ file upload. Bản sao lưu tự động trước khi khôi phục: "' . $autoBackupName . '".');
    }

    /**
     * Download a specific backup file.
     */
    public function download($filename)
    {
        $filename = basename($filename);
        $filePath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($filePath)) {
            return redirect('/admin/backup')->with('error', 'File không tồn tại.');
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

        return redirect('/admin/backup')->with('error', 'File không tồn tại.');
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
