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


        $conn = $this->em->getConnection();

        $namHienTai = date('Y');

        // Truy vấn tổng doanh thu của các đơn hoàn thành trong năm hiện tại
        $sql = "
        SELECT SUM(tong_tien) AS doanhThu
        FROM don_hang
        WHERE trang_thai = :tt
          AND YEAR(ngay_tao) = :nam
    ";
        $row = $conn->fetchAssociative($sql, [
            'tt'  => 'hoan_thanh',
            'nam' => $namHienTai,
        ]);
        // Nếu null thì coi như 0
        $doanh_thu = $row['doanhThu'] !== null
            ? (float)$row['doanhThu']
            : 0.0;


        admin_view('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'tong_sp_count' => $tong_sp_count,
            'don_hang_count' => $don_hang_count,
            'kich_hoat_sp_count' => $kich_hoat_sp_count,
            'noi_bat_sp_count' => $noi_bat_sp_count,
            'doanh_thu'       => $doanh_thu,
            'nguoi_dung_count' => $nguoi_dung_count,
        ]);
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }


//    private function getDoanhThu(): float
//    {
//        $qb = $this->em->createQueryBuilder();
//
//        $qb->select('SUM(d.tongTien)')
//            ->from(DonHang::class, 'd')
//            ->where('d.trangThai = :trangThai') // Add a condition
//            ->setParameter('trangThai', 'da_hoan_thanh'); // Bind the parameter
//
//        $total = $qb->getQuery()->getSingleScalarResult();
//
//        return (float) $total;
//    }
}