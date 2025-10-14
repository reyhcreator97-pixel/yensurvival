<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UsersModel();
    }

    private function uid()
    {
        return (int) user_id();
    }

    public function index()
    {
        $uid = $this->uid();

        // 🔍 Ambil filter & pencarian
        $search = trim($this->request->getGet('search'));
        $status = $this->request->getGet('status'); // all | active | suspend

        // 🔧 Base query
        $builder = $this->userModel
            ->select('id, username, email, active, created_at, updated_at')
            ->where('id !=', $uid);

        if ($status === 'active') {
            $builder->where('active', 1);
        } elseif ($status === 'suspend') {
            $builder->where('active', 0);
        }

        if ($search) {
            $builder->groupStart()
                ->like('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $list = $builder->orderBy('id', 'ASC')->findAll();

        $data = [
            'title'  => 'Kelola Pengguna',
            'list'   => $list,
            'search' => $search,
            'status' => $status,
        ];

        return view('admin/users', $data);
    }

    // ✅ Suspend user
    public function suspend($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->update($id, ['active' => 0]);
        return redirect()->to('/admin/users')->with('message', 'User berhasil disuspend.');
    }

    // ✅ Aktifkan user
    public function activate($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->update($id, ['active' => 1]);
        return redirect()->to('/admin/users')->with('message', 'User berhasil diaktifkan kembali.');
    }

    // ✅ Hapus user
    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('message', 'User berhasil dihapus.');
    }

    // ✅ Reset password (opsional)
    public function resetPassword($id)
    {
        $newPass = 'User' . rand(1000, 9999); // contoh random
        $this->userModel->update($id, [
            'password_hash' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/admin/users')->with(
            'message',
            "Password user ID {$id} berhasil direset menjadi: <strong>{$newPass}</strong>"
        );
    }
}
