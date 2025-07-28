<?php

namespace App\Controllers\Admin;

use App\Models\SanPham;
use App\Models\BienTheSanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;

class SanPhamController
{
    private $em;

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
        $qb->select('sp')
           ->from(SanPham::class, 'sp')
           ->orderBy('sp.ngayTao', 'DESC')
           ->setFirstResult($offset)
           ->setMaxResults($limit);

        $sanPhams = $qb->getQuery()->getResult();

        $totalQb = $this->em->createQueryBuilder();
        $total = $totalQb->select('COUNT(sp.id)')
                        ->from(SanPham::class, 'sp')
                        ->getQuery()
                        ->getSingleScalarResult();

        $totalPages = ceil($total / $limit);

        admin_view('admin/san-pham/danh-sach', [
            'pageTitle' => 'Quản lý Sản phẩm',
            'sanPhams' => $sanPhams,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }

    public function them()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleThem();
            return;
        }

        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/san-pham/them', [
            'pageTitle' => 'Thêm Sản phẩm',
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
        ]);
    }

    public function sua($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($sanPham);
            return;
        }

        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/san-pham/sua', [
            'pageTitle' => 'Sửa Sản phẩm',
            'sanPham' => $sanPham,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
        ]);
    }

    public function chiTiet($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        admin_view('admin/san-pham/chi-tiet', [
            'pageTitle' => 'Chi tiết Sản phẩm',
            'sanPham' => $sanPham
        ]);
    }

    public function xoa($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($sanPham);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa sản phẩm thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm!';
            }
        }

        header('Location: /admin/san-pham');
        exit;
    }

    private function handleThem()
    {
        try {
            $sanPham = new SanPham();
            $this->fillSanPhamData($sanPham);
            
            $this->em->persist($sanPham);
            $this->em->flush();
            
            $_SESSION['success'] = 'Thêm sản phẩm thành công!';
            header('Location: /admin/san-pham');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/san-pham/them', [
                'pageTitle' => 'Thêm Sản phẩm',
                'error' => $error,
                'danhMucs' => $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true]),
                'thuongHieus' => $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true])
            ]);
        }
    }

    private function handleSua($sanPham)
    {
        try {
            $this->fillSanPhamData($sanPham);
            $sanPham->setNgayCapNhat();
            
            $this->em->flush();
            
            $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
            header('Location: /admin/san-pham');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/san-pham/sua', [
                'pageTitle' => 'Sửa Sản phẩm',
                'error' => $error,
                'sanPham' => $sanPham,
                'danhMucs' => $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true]),
                'thuongHieus' => $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true])
            ]);
        }
    }

    private function fillSanPhamData($sanPham)
    {
        $sanPham->setTen($_POST['ten'] ?? '');
        $sanPham->setMaSanPham($_POST['ma_san_pham'] ?? '');
        $sanPham->setMoTaNgan($_POST['mo_ta_ngan'] ?? '');
        $sanPham->setMoTa($_POST['mo_ta'] ?? '');
        $sanPham->setGia(floatval($_POST['gia'] ?? 0));
        $sanPham->setGiaSoSanh(floatval($_POST['gia_so_sanh'] ?? 0));
        $sanPham->setKichHoat(isset($_POST['kich_hoat']));
        $sanPham->setNoiBat(isset($_POST['noi_bat']));
        $sanPham->setSpMoi(isset($_POST['sp_moi']));

        $sanPham->setDuongDan($_POST['duong_dan']);

        if (!empty($_POST['danh_muc_id'])) {
            $danhMuc = $this->em->getRepository(DanhMuc::class)->find($_POST['danh_muc_id']);
            if ($danhMuc) {
                $sanPham->setDanhMuc($danhMuc);
            }
        }

        if (!empty($_POST['thuong_hieu_id'])) {
            $thuongHieu = $this->em->getRepository(ThuongHieu::class)->find($_POST['thuong_hieu_id']);
            if ($thuongHieu) {
                $sanPham->setThuongHieu($thuongHieu);
            }
        }
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