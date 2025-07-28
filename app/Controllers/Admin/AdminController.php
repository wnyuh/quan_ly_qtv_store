<?php

namespace App\Controllers\Admin;

use App\Models\Admin;

class AdminController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../../config/doctrine.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
                admin_view('admin/login', ['error' => $error]);
                return;
            }

            $admin = $this->em->getRepository(Admin::class)->findOneBy(['username' => $username]);

            if ($admin && $admin->verifyPassword($password)) {
                session_start();
                $_SESSION['admin_id'] = $admin->getId();
                $_SESSION['admin_username'] = $admin->getUsername();
                
                header('Location: /admin/dashboard');
                exit;
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
                admin_view('admin/login', ['error' => $error]);
                return;
            }
        }

        admin_view('admin/login');
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        session_destroy();
        
        header('Location: /admin/login');
        exit;
    }

    public function dashboard()
    {
        $this->requireAuth();
        
        admin_view('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard'
        ]);
    }

    private function requireAuth()
    {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}