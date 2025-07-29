<?php

namespace App\Controllers\Admin;

use App\Models\MaGiamGia;
use Doctrine\DBAL\Exception;

class MaGiamGiaController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../../config/doctrine.php';
        $this->requireAuth();
    }

    // 1. Danh sách mã giảm giá
    public function danhSach()
    {
        $page  = max(1, intval($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $qb = $this->em->createQueryBuilder();
        $qb->select('m')
            ->from(MaGiamGia::class, 'm')
            ->orderBy('m.ngayTao', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $rows = $qb->getQuery()->getResult();

        $totalQb = $this->em->createQueryBuilder();
        $total = $totalQb->select('COUNT(m.id)')
            ->from(MaGiamGia::class, 'm')
            ->getQuery()
            ->getSingleScalarResult();
        $totalPages = ceil($total / $limit);

        admin_view('admin/ma-giam-gia/danh-sach', [
            'pageTitle'  => 'Quản lý Mã giảm giá',
            'rows'       => $rows,
            'currentPage'=> $page,
            'totalPages' => $totalPages,
        ]);
    }

    // 2. Thêm mới
    public function them()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleThem();
            return;
        }
        admin_view('admin/ma-giam-gia/them', [
            'pageTitle' => 'Thêm Mã giảm giá'
        ]);
    }

    // 3. Sửa
    public function sua($id)
    {
        $ma = $this->em->getRepository(MaGiamGia::class)->find($id);
        if (!$ma) {
            header('Location: /admin/ma-giam-gia');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($ma);
            return;
        }

        admin_view('admin/ma-giam-gia/sua', [
            'pageTitle' => 'Sửa Mã giảm giá',
            'ma'        => $ma
        ]);
    }

    // 4. Xem chi tiết
    public function chiTiet($id)
    {
        $ma = $this->em->getRepository(MaGiamGia::class)->find($id);
        if (!$ma) {
            header('Location: /admin/ma-giam-gia');
            exit;
        }
        admin_view('admin/ma-giam-gia/chi-tiet', [
            'pageTitle' => 'Chi tiết Mã giảm giá',
            'ma'        => $ma
        ]);
    }

    // 5. Xóa
    public function xoa($id)
    {
        $ma = $this->em->getRepository(MaGiamGia::class)->find($id);
        if ($ma && $_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($ma);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa mã giảm giá thành công.';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Không thể xóa: ' . $e->getMessage();
            }
        }
        header('Location: /admin/ma-giam-gia');
        exit;
    }

    // Xử lý lưu khi thêm
    private function handleThem()
    {
        $ma = new MaGiamGia();
        try {
            $this->fillData($ma);
            $this->em->persist($ma);
            $this->em->flush();
            $_SESSION['success'] = 'Thêm mã giảm giá thành công.';
            header('Location: /admin/ma-giam-gia');
            exit;
        } catch (Exception $e) {
            $error = 'Lỗi: ' . $e->getMessage();
            admin_view('admin/ma-giam-gia/them', [
                'pageTitle' => 'Thêm Mã giảm giá',
                'error'     => $error,
            ]);
        }
    }

    // Xử lý lưu khi sửa
    private function handleSua(MaGiamGia $ma)
    {
        try {
            $this->fillData($ma);
            $ma->setNgayCapNhat();
            $this->em->flush();
            $_SESSION['success'] = 'Cập nhật mã giảm giá thành công.';
            header('Location: /admin/ma-giam-gia');
            exit;
        } catch (Exception $e) {
            $error = 'Lỗi: ' . $e->getMessage();
            admin_view('admin/ma-giam-gia/sua', [
                'pageTitle' => 'Sửa Mã giảm giá',
                'error'     => $error,
                'ma'        => $ma
            ]);
        }
    }

    // Điền dữ liệu từ form vào entity
    private function fillData(MaGiamGia $ma)
    {
        $ma->setMaGiamGia($_POST['ma_giam_gia'] ?? '');
        $ma->setTen($_POST['ten'] ?? '');
        $ma->setLoaiGiamGia($_POST['loai_giam_gia'] ?? 'so_tien');
        $ma->setGiaTriGiam(floatval($_POST['gia_tri_giam'] ?? 0));
        $ma->setGiaTriDonHangToiThieu(
            isset($_POST['gia_tri_don_hang_toi_thieu'])
                ? floatval($_POST['gia_tri_don_hang_toi_thieu'])
                : null
        );
        $ma->setSoLuongToiDa(
            isset($_POST['so_luong_toi_da'])
                ? intval($_POST['so_luong_toi_da'])
                : null
        );
        $ma->setNgayBatDau(new \DateTime($_POST['ngay_bat_dau'] ?? 'now'));
        $ma->setNgayKetThuc(new \DateTime($_POST['ngay_ket_thuc'] ?? 'now'));
        $ma->setKichHoat(isset($_POST['kich_hoat']));
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
