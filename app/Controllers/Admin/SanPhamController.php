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
        
        // Get search parameters
        $search = trim($_GET['search'] ?? '');
        $danhMucId = intval($_GET['danh_muc'] ?? 0);
        $thuongHieuId = intval($_GET['thuong_hieu'] ?? 0);
        $trangThai = $_GET['trang_thai'] ?? '';
        $noiBat = $_GET['noi_bat'] ?? '';
        $spMoi = $_GET['sp_moi'] ?? '';

        // Build query with search conditions
        $qb = $this->em->createQueryBuilder();
        $qb->select('sp')
           ->from(SanPham::class, 'sp')
           ->leftJoin('sp.danhMuc', 'dm')
           ->leftJoin('sp.thuongHieu', 'th')
           ->orderBy('sp.ngayTao', 'DESC');

        // Apply search filters
        if (!empty($search)) {
            $qb->andWhere('sp.ten LIKE :search OR sp.maSanPham LIKE :search OR sp.moTaNgan LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($danhMucId > 0) {
            $qb->andWhere('sp.danhMuc = :danhMucId')
               ->setParameter('danhMucId', $danhMucId);
        }

        if ($thuongHieuId > 0) {
            $qb->andWhere('sp.thuongHieu = :thuongHieuId')
               ->setParameter('thuongHieuId', $thuongHieuId);
        }

        if ($trangThai !== '') {
            $qb->andWhere('sp.kichHoat = :trangThai')
               ->setParameter('trangThai', $trangThai === '1');
        }

        if ($noiBat === '1') {
            $qb->andWhere('sp.noiBat = :noiBat')
               ->setParameter('noiBat', true);
        }

        if ($spMoi === '1') {
            $qb->andWhere('sp.spMoi = :spMoi')
               ->setParameter('spMoi', true);
        }

        // Get total count for pagination
        $totalQb = clone $qb;
        $total = $totalQb->select('COUNT(sp.id)')
                        ->setFirstResult(0)
                        ->setMaxResults(null)
                        ->getQuery()
                        ->getSingleScalarResult();

        // Apply pagination
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        $sanPhams = $qb->getQuery()->getResult();
        $totalPages = ceil($total / $limit);

        // Get filter options
        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/san-pham/danh-sach', [
            'pageTitle' => 'Quản lý Sản phẩm',
            'sanPhams' => $sanPhams,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'selectedDanhMuc' => $danhMucId,
            'selectedThuongHieu' => $thuongHieuId,
            'selectedTrangThai' => $trangThai,
            'selectedNoiBat' => $noiBat,
            'selectedSpMoi' => $spMoi,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
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