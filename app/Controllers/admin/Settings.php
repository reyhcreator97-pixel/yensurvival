<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

helper ('filesystem');

class Settings extends BaseController
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new SettingModel();
    }

    public function index()
    {
        // Ambil konfigurasi global (hanya 1 row)
        $data = $this->setting->first() ?? [
            'id' => 0,
            'currency' => '¥',
            'price_monthly' => 1000,
            'price_yearly' => 10000,
            'backup_schedule' => 'weekly',
            'contact_whatsapp' => '',
        ];

        // Cek file backup terbaru
        $latestBackup = null;
        $backupPath = WRITEPATH . 'backups/';
        if (is_dir($backupPath)) {
            $files = glob($backupPath . 'backup_*.sql');
            if (!empty($files)) {
                usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
                $latestBackup = date('d M Y H:i', filemtime($files[0]));
            }
        }

        return view('admin/settings', [
            'title' => 'Pengaturan Sistem',
            'config' => $data,
            'latestBackup' => $latestBackup
        ]);
    }

    public function save()
    {
        helper('log');

        $id = $this->request->getPost('id');
        $data = [
            'currency'         => $this->request->getPost('currency'),
            'price_monthly'    => (float) $this->request->getPost('price_monthly'),
            'price_yearly'     => (float) $this->request->getPost('price_yearly'),
            'backup_schedule'  => $this->request->getPost('backup_schedule'),
            'contact_whatsapp' => $this->request->getPost('contact_whatsapp'),
        ];

        if ($id) {
            $this->setting->update($id, $data);
        } else {
            $this->setting->insert($data);
        }

        log_activity('Ubah Pengaturan', 'Admin memperbarui konfigurasi sistem.');

        return redirect()->to('/admin/settings')->with('message', 'Pengaturan berhasil disimpan.');
    }

    public function backup()
    {
        try {
            $db = \Config\Database::connect();
            $backupPath = WRITEPATH . 'backups/';
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0777, true);
            }
    
            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . $fileName;
    
            $backupSql = "-- Backup Database: " . $db->database . "\n";
            $backupSql .= "-- Created at: " . date('Y-m-d H:i:s') . "\n\n";
    
            // Ambil semua tabel
            $tables = $db->listTables();
    
            foreach ($tables as $table) {
                // Struktur tabel
                $result = $db->query("SHOW CREATE TABLE $table")->getResultArray();
                if (!isset($result[0]['Create Table'])) continue;
                $backupSql .= "\n\n" . $result[0]['Create Table'] . ";\n\n";
    
                // Data tabel
                $rows = $db->query("SELECT * FROM $table")->getResultArray();
                foreach ($rows as $row) {
                    $vals = array_map(fn($v) => $db->escape($v), $row);
                    $backupSql .= "INSERT INTO $table VALUES (" . implode(',', $vals) . ");\n";
                }
            }
    
            // Simpan ke file
            if (!write_file($filePath, $backupSql)) {
                throw new \Exception('Gagal menyimpan file backup ke writable/backups/');
            }
    
            log_activity('Backup Database', "Admin membuat backup database manual: {$fileName}");
    
            return redirect()->back()->with('message', 'Backup database lokal berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Backup database gagal: ' . $e->getMessage());
        }
    }

    public function downloadBackup()
    {
        helper(['filesystem', 'log']);

        $backupPath = WRITEPATH . 'backups/';
        if (!is_dir($backupPath)) {
            return redirect()->back()->with('error', 'Folder backup belum dibuat.');
        }

        $files = glob($backupPath . 'backup_*.sql');
        if (empty($files)) {
            return redirect()->back()->with('error', 'Belum ada file backup yang tersedia.');
        }

        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        $latestFile = $files[0];
        $fileName   = basename($latestFile);

        log_activity('Download Backup', "Admin mengunduh file backup: {$fileName}");

        return $this->response->download($latestFile, null)->setFileName($fileName);
    }

    public function autoBackup()
{
    helper(['filesystem', 'log']); // aktifkan helper log()

    $config = $this->setting->first();
    if (!$config) {
        log_activity('AUTO_BACKUP_FAILED', 'Konfigurasi tidak ditemukan.');
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Konfigurasi belum diatur.'
        ]);
    }

    $schedule = $config['backup_schedule'] ?? 'weekly';

    // File penanda backup terakhir
    $flagFile = WRITEPATH . 'backups/last_backup.txt';
    $lastBackup = file_exists($flagFile) ? strtotime(file_get_contents($flagFile)) : 0;
    $now = time();

    // Tentukan interval backup
    if ($schedule == 'daily') {
        $interval = 86400; // 1 hari
    } elseif ($schedule == 'weekly') {
        $interval = 604800; // 7 hari
    } elseif ($schedule == 'monthly') {
        $interval = 2592000; // 30 hari
    } else {
        $interval = 604800; // default mingguan
    }

    // Jika belum waktunya backup, skip
    if ($now - $lastBackup < $interval) {
        log_activity('AUTO_BACKUP_SKIPPED', "Backup otomatis dilewati (belum waktunya).");
        return $this->response->setJSON([
            'status' => 'skip',
            'message' => 'Belum waktunya backup berikutnya.'
        ]);
    }

    // Jalankan backup
    try {
        $db = \Config\Database::connect();
        $backupPath = WRITEPATH . 'backups/';
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true);
        }
    
        $fileName = 'backup_' . date('Ymd_His') . '.sql';
        $filePath = $backupPath . $fileName;
    
        $tables = $db->query('SHOW TABLES')->getResultArray();
        $sql = '';
    
        foreach ($tables as $tableRow) {
            $tableName = array_values($tableRow)[0];
    
            // Struktur tabel
            $createTable = $db->query("SHOW CREATE TABLE $tableName")->getRowArray();
            $sql .= "\n\n" . $createTable['Create Table'] . ";\n\n";
    
            // Data tabel
            $rows = $db->query("SELECT * FROM $tableName")->getResultArray();
            foreach ($rows as $row) {
                $vals = array_map(fn($v) => $db->escape($v), $row);
                $sql .= "INSERT INTO $tableName VALUES (" . implode(',', $vals) . ");\n";
            }
        }
    
        // Simpan file backup
        file_put_contents($filePath, $sql);
        file_put_contents($flagFile, date('Y-m-d H:i:s'));
    
        // Log aktivitas
        log_activity('AUTO_BACKUP_SUCCESS', "Backup otomatis berhasil: {$fileName}");
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Backup otomatis berhasil dibuat: {$fileName}"
        ]);
    } catch (\Throwable $e) {
        log_activity('AUTO_BACKUP_ERROR', "Gagal membuat backup otomatis: " . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal membuat backup otomatis.'
        ]);
    }
}
}