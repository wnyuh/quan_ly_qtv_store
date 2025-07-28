<?php

namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\DonHang;
use App\Models\NguoiDung;
use App\Models\SanPham;

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
        // Lấy dữ  liệu của bảng sản phẩm
        $san_pham_repository = $this->em->getRepository(SanPham::class);

        $tong_sp_count = $san_pham_repository->count([]);

        $kich_hoat_sp_count = $san_pham_repository->count(['kichHoat' => true]);

        $noi_bat_sp_count = $san_pham_repository->count(['noiBat' => true, 'kichHoat' => true]);

        // Lấy dữ  liệu của bảng đơn hàng
        $don_hang_repository = $this->em->getRepository(DonHang::class);

        $don_hang_count = $don_hang_repository->count([]);
        // Lấy dữ  liệu của khách hàng
        $nguoi_dung_repository = $this->em->getRepository(NguoiDung::class);

        $nguoi_dung_count = $nguoi_dung_repository->count([]);


        admin_view('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'tong_sp_count' => $tong_sp_count,
            'don_hang_count' => $don_hang_count,
            'kich_hoat_sp_count' => $kich_hoat_sp_count,
            'noi_bat_sp_count' => $noi_bat_sp_count,
            'doanh_thu' => $this->getDoanhThu(),
            'nguoi_dung_count' => $nguoi_dung_count,
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

    private function getDoanhThu(): float
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('SUM(d.tongTien)')
            ->from(DonHang::class, 'd')
            ->where('d.trangThai = :trangThai') // Add a condition
            ->setParameter('trangThai', 'da_giao_hang'); // Bind the parameter

        $total = $qb->getQuery()->getSingleScalarResult();

        return (float) $total;
    }
}