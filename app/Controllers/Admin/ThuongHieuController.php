<?php

namespace App\Controllers\Admin;

use App\Models\ThuongHieu;
use Doctrine\ORM\EntityManagerInterface;

class ThuongHieuController
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
        $qb->select('th')
            ->from(ThuongHieu::class, 'th')
            ->orderBy('th.ten', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $thuongHieus = $qb->getQuery()->getResult();

        $totalQb = $this->em->createQueryBuilder();
        $total = $totalQb->select('COUNT(th.id)')
            ->from(ThuongHieu::class, 'th')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($total / $limit);

        admin_view('admin/thuong-hieu/danh-sach', [
            'pageTitle'   => 'Quản lý Thương hiệu',
            'thuongHieus' => $thuongHieus,
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

        admin_view('admin/thuong-hieu/them', [
            'pageTitle' => 'Thêm Thương hiệu',
        ]);
    }

    public function sua(int $id)
    {
        $thuongHieu = $this->em->getRepository(ThuongHieu::class)->find($id);
        if (!$thuongHieu) {
            header('Location: /admin/thuong-hieu');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($thuongHieu);
            return;
        }

        admin_view('admin/thuong-hieu/sua', [
            'pageTitle'    => 'Sửa Thương hiệu',
            'thuongHieu'   => $thuongHieu,
        ]);
    }

    public function chiTiet(int $id)
    {
        $thuongHieu = $this->em->getRepository(ThuongHieu::class)->find($id);
        if (!$thuongHieu) {
            header('Location: /admin/thuong-hieu');
            exit;
        }

        admin_view('admin/thuong-hieu/chi-tiet', [
            'pageTitle'  => 'Chi tiết Thương hiệu',
            'thuongHieu' => $thuongHieu,
        ]);
    }

    public function xoa(int $id)
    {
        $thuongHieu = $this->em->getRepository(ThuongHieu::class)->find($id);
        if (!$thuongHieu) {
            header('Location: /admin/thuong-hieu');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($thuongHieu);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa thương hiệu thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thương hiệu!';
            }
        }

        header('Location: /admin/thuong-hieu');
        exit;
    }

    private function handleThem(): void
    {
        try {
            $thuongHieu = new ThuongHieu();
            $this->fillThuongHieuData($thuongHieu);

            $this->em->persist($thuongHieu);
            $this->em->flush();

            $_SESSION['success'] = 'Thêm thương hiệu thành công!';
            header('Location: /admin/thuong-hieu');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/thuong-hieu/them', [
                'pageTitle' => 'Thêm Thương hiệu',
                'error'     => $error,
            ]);
        }
    }

    private function handleSua(ThuongHieu $thuongHieu): void
    {
        try {
            $this->fillThuongHieuData($thuongHieu);
            $thuongHieu->setNgayCapNhat();

            $this->em->flush();

            $_SESSION['success'] = 'Cập nhật thương hiệu thành công!';
            header('Location: /admin/thuong-hieu');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/thuong-hieu/sua', [
                'pageTitle'  => 'Sửa Thương hiệu',
                'error'      => $error,
                'thuongHieu' => $thuongHieu,
            ]);
        }
    }

    private function fillThuongHieuData(ThuongHieu $thuongHieu): void
    {
        $thuongHieu->setTen($_POST['ten'] ?? '');
        $thuongHieu->setDuongDan($_POST['duong_dan'] ?? '');
        $thuongHieu->setLogo($_POST['logo'] ?? null);
        $thuongHieu->setMoTa($_POST['mo_ta'] ?? null);
        $thuongHieu->setWebsite($_POST['website'] ?? null);
        $thuongHieu->setKichHoat(isset($_POST['kich_hoat']));
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}