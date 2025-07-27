<?php
// app/Models/donhang.php

class donhang
{
    /** @var PDO */
    protected $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả đơn hàng, kèm thông tin người dùng và địa chỉ
     * @return array
     */
    public function all()
    {
        $sql = "
            SELECT dh.*, nd.ten_dang_nhap, dc.dia_chi, dc.ten_nguoi_nhan
            FROM don_hang dh
            JOIN nguoi_dung nd ON dh.ma_nguoi_dung = nd.ma_nguoi_dung
            JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi_giao_hang = dc.ma_dia_chi
            ORDER BY dh.ngay_dat_hang DESC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm một đơn hàng theo ID, kèm chi tiết đơn và thanh toán
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        // Đơn hàng
        $stmt = $this->pdo->prepare(
            "SELECT * FROM don_hang WHERE ma_don_hang = :id"
        );
        $stmt->execute([':id' => (int)$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return null;
        }

        // Chi tiết đơn hàng
        $stmt = $this->pdo->prepare(
            "SELECT ct.*, sp.ten_san_pham, sp.hinh_anh 
             FROM chi_tiet_don_hang ct
             JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
             WHERE ct.ma_don_hang = :id"
        );
        $stmt->execute([':id' => (int)$id]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Thông tin thanh toán
        $stmt = $this->pdo->prepare(
            "SELECT * FROM thanh_toan WHERE ma_don_hang = :id"
        );
        $stmt->execute([':id' => (int)$id]);
        $order['payment'] = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order;
    }

    /**
     * Tạo đơn hàng mới (trả về ID đơn vừa tạo)
     * @param array $data
     *   + ma_nguoi_dung
     *   + ma_dia_chi_giao_hang
     *   + trang_thai (ví dụ 'moi', 'dang_xu_ly')
     * @return int
     */
    public function create($data)
    {
        $sql = "
            INSERT INTO don_hang 
            (ma_nguoi_dung, ma_dia_chi_giao_hang, trang_thai)
            VALUES (:mnd, :mdc, :tt)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':mnd' => (int)$data['ma_nguoi_dung'],
            ':mdc' => (int)$data['ma_dia_chi_giao_hang'],
            ':tt'  => $data['trang_thai'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật trạng thái hoặc địa chỉ giao hàng
     * @param int   $id
     * @param array $data (có thể gồm trang_thai, ma_dia_chi_giao_hang)
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (isset($data['trang_thai'])) {
            $fields[]        = "trang_thai = :tt";
            $params[':tt']   = $data['trang_thai'];
        }
        if (isset($data['ma_dia_chi_giao_hang'])) {
            $fields[]             = "ma_dia_chi_giao_hang = :mdc";
            $params[':mdc']       = (int)$data['ma_dia_chi_giao_hang'];
        }

        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE don_hang SET " . implode(', ', $fields) . " WHERE ma_don_hang = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa đơn hàng theo ID (bao gồm chi tiết và thanh toán)
     * @param int $id
     */
    public function delete($id)
    {
        $id = (int)$id;
        // Xóa chi tiết đơn
        $this->pdo->prepare("DELETE FROM chi_tiet_don_hang WHERE ma_don_hang = ?")
            ->execute([$id]);
        // Xóa thanh toán
        $this->pdo->prepare("DELETE FROM thanh_toan WHERE ma_don_hang = ?")
            ->execute([$id]);
        // Xóa chính đơn hàng
        $this->pdo->prepare("DELETE FROM don_hang WHERE ma_don_hang = ?")
            ->execute([$id]);
    }
}
