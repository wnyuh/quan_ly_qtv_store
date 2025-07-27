<?php
// app/Controllers/qly_donhang_controller.php

require __DIR__ . '/../Models/donhang.php';
require __DIR__ . '/../Models/chitietdonhang.php';
require __DIR__ . '/../Models/nguoidung.php';

class qly_donhang_controller
{
    private $mDh, $mCt, $mNd;

    public function __construct(PDO $pdo)
    {
        $this->mDh = new DonHang($pdo);
        $this->mCt = new chitietdonhang($pdo);
        $this->mNd = new NguoiDung($pdo);
    }

    // DS đơn hàng
    public function index()
    {
        $dhs = $this->mDh->all();
        include __DIR__ . '/../Views/layouts/admin_head.php';
        include __DIR__ . '/../Views/admin/qly_donhang.php';
    }

    // Xem chi tiết
    public function show()
    {
        $id     = (int)($_GET['id'] ?? 0);
        $dh     = $this->mDh->find($id);
        $ct     = $this->mCt->byDonHang($id);
        $kh     = $this->mNd->find($dh['ma_nguoi_dung']);
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/xem_donhang.php';
    }

    // cập nhật trạng thái
    public function update()
    {
        $id    = (int)($_POST['id'] ?? 0);
        $tt    = trim($_POST['trang_thai'] ?? '');
        $this->mDh->update($id, ['trang_thai' => $tt]);
        header('Location: index.php?url=quan-ly-dh/show&id=' . $id);
    }

    // xóa đơn
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->mDh->delete($id);
        header('Location: index.php?url=quan-ly-dh/index');
    }
}
