<?php

namespace App\Controllers\Admin;

use App\Models\NguoiDung;

class NguoiDungController
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
        $qb->select('u')
            ->from(NguoiDung::class, 'u')
            ->orderBy('u.ngayTao', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $users = $qb->getQuery()->getResult();

        $totalQb = $this->em->createQueryBuilder();
        $total = $totalQb->select('COUNT(u.id)')
            ->from(NguoiDung::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($total / $limit);

        admin_view('admin/nguoi-dung/danh-sach', [
            'pageTitle'   => 'Quản lý Người dùng',
            'users'       => $users,
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

        admin_view('admin/nguoi-dung/them', [
            'pageTitle' => 'Thêm Người dùng',
        ]);
    }

    public function sua($id)
    {
        $user = $this->em->getRepository(NguoiDung::class)->find($id);
        if (!$user) {
            header('Location: /admin/nguoi-dung');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($user);
            return;
        }

        admin_view('admin/nguoi-dung/sua', [
            'pageTitle' => 'Sửa Người dùng',
            'user'      => $user,
        ]);
    }

    public function chiTiet($id)
    {
        $user = $this->em->getRepository(NguoiDung::class)->find($id);
        if (!$user) {
            header('Location: /admin/nguoi-dung');
            exit;
        }

        admin_view('admin/nguoi-dung/chi-tiet', [
            'pageTitle' => 'Chi tiết Người dùng',
            'user'      => $user,
        ]);
    }

    public function xoa($id)
    {
        $user = $this->em->getRepository(NguoiDung::class)->find($id);
        if (!$user) {
            header('Location: /admin/nguoi-dung');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($user);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa người dùng thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa người dùng!';
            }
        }

        header('Location: /admin/nguoi-dung');
        exit;
    }

    private function handleThem()
    {
        try {
            $user = new NguoiDung();
            $this->fillNguoiDungData($user);

            $this->em->persist($user);
            $this->em->flush();

            $_SESSION['success'] = 'Thêm người dùng thành công!';
            header('Location: /admin/nguoi-dung');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/nguoi-dung/them', [
                'pageTitle' => 'Thêm Người dùng',
                'error'     => $error,
            ]);
        }
    }

    private function handleSua(NguoiDung $user)
    {
        try {
            $this->fillNguoiDungData($user);
            $this->em->flush();

            $_SESSION['success'] = 'Cập nhật người dùng thành công!';
            header('Location: /admin/nguoi-dung');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/nguoi-dung/sua', [
                'pageTitle' => 'Sửa Người dùng',
                'error'     => $error,
                'user'      => $user,
            ]);
        }
    }

    private function fillNguoiDungData(NguoiDung $user)
    {
        $user->setEmail($_POST['email'] ?? '');
        $user->setMatKhau($_POST['mat_khau'] ?? '');
        $user->setHo($_POST['ho'] ?? '');
        $user->setTen($_POST['ten'] ?? '');
        $user->setSoDienThoai($_POST['so_dien_thoai'] ?? null);

        if (!empty($_POST['ngay_sinh'])) {
            try {
                $user->setNgaySinh(new \DateTime($_POST['ngay_sinh']));
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }
    }

    private function requireAuth()
    {
//        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
