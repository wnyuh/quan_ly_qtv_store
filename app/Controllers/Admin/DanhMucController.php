<?php

namespace App\Controllers\Admin;

use App\Models\DanhMuc;
use Doctrine\ORM\EntityManagerInterface;

class DanhMucController
{
    private EntityManagerInterface $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../../config/doctrine.php';
        $this->requireAuth();
    }

    public function danhSach()
    {
        $page   = max(1, intval($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // Query danh sách
        $qb = $this->em->createQueryBuilder();
        $qb->select('dm')
            ->from(DanhMuc::class, 'dm')
            ->orderBy('dm.thuTu', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $danhMucs = $qb->getQuery()->getResult();

        // Tổng số
        $totalQb = $this->em->createQueryBuilder();
        $total    = $totalQb->select('COUNT(dm.id)')
            ->from(DanhMuc::class, 'dm')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($total / $limit);

        admin_view('admin/danh-muc/danh-sach', [
            'pageTitle'   => 'Quản lý Danh mục',
            'danhMucs'    => $danhMucs,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'total'       => $total,
        ]);
    }

    public function them()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleThem();
            return;
        }

        // Lấy danh mục cha khả dụng
        $parents = $this->em
            ->getRepository(DanhMuc::class)
            ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/danh-muc/them', [
            'pageTitle' => 'Thêm Danh mục',
            'parents'   => $parents,
        ]);
    }

    public function sua(int $id)
    {
        $danhMuc = $this->em->getRepository(DanhMuc::class)->find($id);
        if (!$danhMuc) {
            header('Location: /admin/danh-muc');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($danhMuc);
            return;
        }

        $parents = $this->em
            ->getRepository(DanhMuc::class)
            ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/danh-muc/sua', [
            'pageTitle' => 'Sửa Danh mục',
            'danhMuc'   => $danhMuc,
            'parents'   => $parents,
        ]);
    }

    public function chiTiet(int $id)
    {
        $danhMuc = $this->em->getRepository(DanhMuc::class)->find($id);
        if (!$danhMuc) {
            header('Location: /admin/danh-muc');
            exit;
        }

        admin_view('admin/danh-muc/chi-tiet', [
            'pageTitle' => 'Chi tiết Danh mục',
            'danhMuc'   => $danhMuc,
        ]);
    }

    public function xoa(int $id)
    {
        $danhMuc = $this->em->getRepository(DanhMuc::class)->find($id);
        if (!$danhMuc) {
            header('Location: /admin/danh-muc');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($danhMuc);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa danh mục thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa danh mục!';
            }
        }

        header('Location: /admin/danh-muc');
        exit;
    }

    private function handleThem(): void
    {
        try {
            $danhMuc = new DanhMuc();
            $this->fillDanhMucData($danhMuc);

            $this->em->persist($danhMuc);
            $this->em->flush();

            $_SESSION['success'] = 'Thêm danh mục thành công!';
            header('Location: /admin/danh-muc');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            $parents = $this->em
                ->getRepository(DanhMuc::class)
                ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

            admin_view('admin/danh-muc/them', [
                'pageTitle' => 'Thêm Danh mục',
                'error'     => $error,
                'parents'   => $parents,
            ]);
        }
    }

    private function handleSua(DanhMuc $danhMuc): void
    {
        try {
            $this->fillDanhMucData($danhMuc);
            $danhMuc->setNgayCapNhat();

            $this->em->flush();

            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
            header('Location: /admin/danh-muc');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            $parents = $this->em
                ->getRepository(DanhMuc::class)
                ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

            admin_view('admin/danh-muc/sua', [
                'pageTitle' => 'Sửa Danh mục',
                'error'     => $error,
                'danhMuc'   => $danhMuc,
                'parents'   => $parents,
            ]);
        }
    }

    private function fillDanhMucData(DanhMuc $danhMuc): void
    {
        $danhMuc->setTen($_POST['ten'] ?? '');
        $danhMuc->setDuongDan($_POST['duong_dan'] ?? '');
        $danhMuc->setMoTa($_POST['mo_ta'] ?? null);
        $danhMuc->setHinhAnh($_POST['hinh_anh'] ?? null);
        $danhMuc->setThuTu(intval($_POST['thu_tu'] ?? 0));
        $danhMuc->setKichHoat(isset($_POST['kich_hoat']));

        // Danh mục cha
        if (!empty($_POST['danh_muc_cha_id'])) {
            $parent = $this->em
                ->getRepository(DanhMuc::class)
                ->find($_POST['danh_muc_cha_id']);
            if ($parent) {
                $danhMuc->setDanhMucCha($parent);
            }
        } else {
            $danhMuc->setDanhMucCha(null);
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
