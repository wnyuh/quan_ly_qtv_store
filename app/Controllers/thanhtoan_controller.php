<?php
// app/Controllers/thanhtoan_controller.php

class thanhtoan_controller
{
    protected $ttModel;
    protected $dhModel;
    protected $ctModel;

    public function __construct(PDO $pdo)
    {
        require_once __DIR__ . '/../Models/donhang.php';
        require_once __DIR__ . '/../Models/chitietdonhang.php';
        require_once __DIR__ . '/../Models/thanhtoan.php';
        $this->dhModel = new DonHang($pdo);
        $this->ctModel = new chitietdonhang($pdo);
        $this->ttModel = new ThanhToan($pdo);

        // session only
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // user must be logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    /**
     * GET /?url=thanh-toan/form
     * Hiển thị form thanh toán: liệt kê giỏ, tổng tiền, chọn phương thức
     */
    public function form()
    {
        // lấy giỏ hàng từ model
        $uid   = $_SESSION['user']['ma_nguoi_dung'];
        $items = $this->ctModel->allByUserCart($uid); // giả sử model hỗ trợ
        $total = array_reduce($items, fn($sum,$i)=> $sum + $i['gia_tai_thoi_diem']*$i['so_luong'], 0);

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/thanhtoan/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * POST /?url=thanh-toan/pay
     * Xử lý đặt hàng & lưu thanh toán
     */
    public function pay()
    {
        $uid = $_SESSION['user']['ma_nguoi_dung'];
        // 1. Tạo đơn hàng
        $dhId = $this->dhModel->create([
            'ma_nguoi_dung'   => $uid,
            'ngay_dat_hang'   => date('Y-m-d H:i:s'),
            'trang_thai'      => 'Chờ xử lý',
            'ma_dia_chi_giao_hang' => (int)($_POST['ma_dia_chi'] ?? 0)
        ]);

        // 2. Chuyển các mục trong giỏ vào chi tiết đơn
        $items = $this->ctModel->allByUserCart($uid);
        foreach ($items as $it) {
            $this->ctModel->create([
                'ma_don_hang'       => $dhId,
                'ma_san_pham'       => $it['ma_san_pham'],
                'so_luong'          => $it['so_luong'],
                'gia_tai_thoi_diem' => $it['gia_tai_thoi_diem'],
            ]);
        }

        // 3. Tính tổng & lưu thanh toán
        $amount = array_reduce($items, fn($s,$i)=> $s + $i['gia_tai_thoi_diem']*$i['so_luong'], 0);
        $method = trim($_POST['phuong_thuc'] ?? 'Tiền mặt');
        $this->ttModel->create([
            'ma_don_hang'    => $dhId,
            'ngay_thanh_toan'=> date('Y-m-d H:i:s'),
            'so_tien'        => $amount,
            'phuong_thuc'    => $method,
            'trang_thai'     => 'Đã thanh toán',
        ]);

        // 4. Xóa giỏ hàng của user
        $this->ctModel->clearCart($uid);

        // 5. Hiển thị kết quả
        $_SESSION['last_order'] = $dhId;
        header('Location: index.php?url=thanh-toan/result');
        exit;
    }

    /**
     * GET /?url=thanh-toan/result
     * Hiển thị kết quả đơn hàng vừa đặt
     */
    public function result()
    {
        $dhId = $_SESSION['last_order'] ?? null;
        if (!$dhId) {
            header('Location: index.php?url=gio-hang/index');
            exit;
        }
        // lấy chi tiết đơn và thanh toán
        $dh   = $this->dhModel->find($dhId);
        $ct   = $this->ctModel->allByOrder($dhId);
        $tt   = $this->ttModel->findByOrder($dhId);

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/thanhtoan/ketqua.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }
}
