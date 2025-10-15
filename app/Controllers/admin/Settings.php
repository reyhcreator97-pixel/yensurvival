<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

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

        return view('admin/settings', [
            'title' => 'Pengaturan Sistem',
            'config' => $data
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $data = [
            'currency'        => $this->request->getPost('currency'),
            'price_monthly'   => (float) $this->request->getPost('price_monthly'),
            'price_yearly'    => (float) $this->request->getPost('price_yearly'),
            'backup_schedule' => $this->request->getPost('backup_schedule'),
            'contact_whatsapp'=> $this->request->getPost('contact_whatsapp'),
        ];

        if ($id) {
            $this->setting->update($id, $data);
        } else {
            $this->setting->insert($data);
        }

        return redirect()->to('/admin/settings')->with('message', 'Pengaturan berhasil disimpan.');
    }

    // Optional: trigger backup manual
    public function backup()
    {
        // simulasi (nanti bisa diganti pakai real backup)
        return redirect()->back()->with('message', 'Backup database berhasil dibuat.');
    }
}