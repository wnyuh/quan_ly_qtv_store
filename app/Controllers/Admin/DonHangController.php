<?php

namespace App\Controllers\Admin;

use App\Models\DonHang;
use Doctrine\ORM\EntityManagerInterface;

class DonHangController
{
    private EntityManagerInterface $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../../config/doctrine.php';
        $this->requireAuth();
    }

    public function danhSach()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $this->em->createQueryBuilder();
        $qb->select('dh')
            ->from(DonHang::class, 'dh')
            ->orderBy('dh.ngayTao', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $donHangs = $qb->getQuery()->getResult();
        $totalQb = $this->em->createQueryBuilder();
        $total = $totalQb->select('COUNT(dh.id)')
            ->from(DonHang::class, 'dh')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($total / $limit);

        admin_view('admin/don-hang/danh-sach', [
            'pageTitle'   => 'Quản lý Đơn hàng',
            'donHangs'    => $donHangs,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'total'       => $total,
        ]);
    }

    public function chiTiet(int $id)
    {
        $donHang = $this->em->getRepository(DonHang::class)->find($id);
        if (!$donHang) {
            header('Location: /admin/don-hang');
            exit;
        }
        admin_view('admin/don-hang/chi-tiet', [
            'pageTitle' => 'Chi tiết Đơn hàng',
            'donHang'   => $donHang,
        ]);
    }

    public function sua(int $id)
    {
        $donHang = $this->em->getRepository(DonHang::class)->find($id);
        if (!$donHang) {
            header('Location: /admin/don-hang');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($donHang);
            return;
        }

        admin_view('admin/don-hang/sua', [
            'pageTitle' => 'Cập nhật Đơn hàng',
            'donHang'   => $donHang,
        ]);
    }

    public function xoa(int $id)
    {
        $donHang = $this->em->getRepository(DonHang::class)->find($id);
        if (!$donHang) {
            header('Location: /admin/don-hang');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($donHang);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa đơn hàng thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi khi xóa đơn hàng!';
            }
        }
        header('Location: /admin/don-hang');
        exit;
    }

    private function handleSua(DonHang $donHang): void
    {
        try {
            if (isset($_POST['trang_thai'])) {
                $donHang->setTrangThai($_POST['trang_thai']);
            }
            if (isset($_POST['trang_thai_thanh_toan'])) {
                $donHang->setTrangThaiThanhToan($_POST['trang_thai_thanh_toan']);
            }
            if (!empty($_POST['ngay_giao'])) {
                $donHang->setNgayGiao(new \DateTime($_POST['ngay_giao']));
            }
            if (!empty($_POST['ngay_nhan'])) {
                $donHang->setNgayNhan(new \DateTime($_POST['ngay_nhan']));
            }
            $donHang->setGhiChu($_POST['ghi_chu'] ?? null);

            $this->em->flush();
            $_SESSION['success'] = 'Cập nhật đơn hàng thành công!';
            header('Location: /admin/don-hang/chi-tiet/' . $donHang->getId());
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/don-hang/sua', [
                'pageTitle' => 'Cập nhật Đơn hàng',
                'error'     => $error,
                'donHang'   => $donHang,
            ]);
        }
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
