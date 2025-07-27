<?php
// app/Controllers/giohang_controller.php

class giohang_controller
{
    /** @var \GioHang */
    protected $ghModel;
    /** @var \SanPham */
    protected $spModel;

    /**
     * @param \PDO $pdo – được inject từ public/index.php
     */
    public function __construct(PDO $pdo)
    {
        // Khởi model
        require_once __DIR__ . '/../Models/giohang.php';
        require_once __DIR__ . '/../Models/sanpham.php';
        $this->ghModel = new giohang($pdo);
        $this->spModel = new sanpham($pdo);

        // Bật session nếu cần
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Chỉ cho user đã đăng nhập
        if (empty($_SESSION['user'])) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    /**
     * GET  ?url=giohang/index
     */
    public function index()
    {
        $userId = $_SESSION['user']['ma_nguoi_dung'];
        $items  = $this->ghModel->allByUser($userId);
        include __DIR__ . '/../Views/giohang/index.php';
    }


    /**
     * POST ?url=giohang/add
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['ma_nguoi_dung'];
            $prodId = (int)($_POST['ma_san_pham'] ?? 0);
            $qty    = max(1, (int)($_POST['so_luong'] ?? 1));

            if ($this->ghModel->exists($userId, $prodId)) {
                $this->ghModel->updateQty($userId, $prodId, $qty);
            } else {
                $this->ghModel->add($userId, $prodId, $qty);
            }
        }
        header('Location: index.php?url=giohang/index');
        exit;
    }

    /**
     * POST ?url=giohang/update
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['ma_nguoi_dung'];
            $prodId = (int)($_POST['ma_san_pham'] ?? 0);
            $qty    = max(1, (int)($_POST['so_luong'] ?? 1));
            $this->ghModel->updateQty($userId, $prodId, $qty);
        }
        header('Location: index.php?url=giohang/index');
        exit;
    }

    /**
     * GET ?url=giohang/remove&ma_san_pham=xxx
     */
    public function remove()
    {
        $userId = $_SESSION['user']['ma_nguoi_dung'];
        $prodId = (int)($_GET['ma_san_pham'] ?? 0);
        $this->ghModel->remove($userId, $prodId);

        header('Location: index.php?url=giohang/index');
        exit;
    }
}
