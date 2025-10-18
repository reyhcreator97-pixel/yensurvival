<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\UsersModel;

class Subscription extends BaseController
{
    protected $subs;
    protected $users;

    public function __construct()
    {
        $this->subs  = new SubscriptionModel();
        $this->users = new UsersModel();
    }

    public function index()
    {
        $data['title'] = 'Kelola Subscription';
        $data['subs']  = $this->subs
            ->select('subscriptions.*, users.username, users.email')
            ->join('users', 'users.id = subscriptions.user_id', 'left')
            ->orderBy('subscriptions.id', 'DESC')
            ->findAll();

        return view('admin/subscription', $data);
    }

    public function edit($id)
    {
        $sub = $this->subs->find($id);
        if (!$sub) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Subscription',
            'sub'   => $sub
        ];

        return view('admin/subscription_edit', $data);
    }

    public function update($id)
    {
        $sub = $this->subs->find($id);
        if (!$sub) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'plan_type' => $this->request->getPost('plan_type'),
            'status'    => $this->request->getPost('status'),
            'end_date'  => $this->request->getPost('end_date'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];

        $this->subs->update($id, $data);
        log_activity('Subcription Update', "Admin men-update subscription user ID: {$id}");
        return redirect()->to('/admin/subscription')->with('message', 'Subscription berhasil diperbarui.');
    }

    public function activate($id)
    {
        $this->subs->update($id, [
            'status' => 'active',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        log_activity('Subcription Aktif', "Admin men-aktifkan subscription user ID: {$id}");
        return redirect()->to('/admin/subscription')->with('message', 'Subscription di aktifkan.');
    }

    public function cancel($id)
    {
        $this->subs->update($id, [
            'status' => 'canceled',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        log_activity('Subcription Cancel', "Admin men-batalkan subscription user ID: {$id}");
        return redirect()->to('/admin/subscription')->with('message', 'Subscription di batalkan.');
    }
}