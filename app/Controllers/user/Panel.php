<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;
use Config\Service;

helper('auth');

class Panel extends BaseController
{
    protected $subscriptionModel;
    protected $userModel;

    public function __construct()
    {
        $this->subscriptionModel = new SubscriptionModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = user_id(); // dari Myth/Auth
        $user = $this->userModel->find($userId);

        // Ambil data subscription user (terbaru)
        $subscription = $this->subscriptionModel
            ->where('user_id', $userId)
            ->orderBy('end_date', 'DESC')
            ->first();

        // Tentukan status
        $isActive = false;
        if ($subscription && strtotime($subscription['end_date']) >= time()) {
            $isActive = true;
        }

        $data = [
            'title' => 'User Panel',
            'user' => $user,
            'subscription' => $subscription,
            'isActive' => $isActive
        ];

        return view('user/panel', $data);
    }

    public function changePassword()
    {
        $auth = service('authentication');
        $user = $auth->user();

        $newPassword = $this->request->getPost('new_password');
        $confirm = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $hash = \Myth\Auth\Password::hash($newPassword); // hash yang di sesuaikan dengan format myth auth

        $this->userModel->update($user->id, [
            'password_hash' => $hash
        ]);

        return redirect()->to('/user/panel')->with('message', 'Password berhasil diubah.');
    }
}