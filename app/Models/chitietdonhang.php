<?php
// app/Models/chitietdonhang.php

class chitietdonhang
{
    /** @var PDO */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả chi tiết đơn hàng theo mã đơn hàng
     * @param int $ma_don_hang
     * @return array
     */
    public function findByOrder(int $ma_don_hang): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * 
             FROM chi_tiet_don_hang 
             WHERE ma_don_hang = :mdh"
        );
        $stmt->execute([':mdh' => $ma_don_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo một dòng chi tiết đơn hàng mới
     * @param array $data  (ma_don_hang, ma_san_pham, so_luong, gia_tai_thoi_diem)
     * @return int ID dòng mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO chi_tiet_don_hang 
                (ma_don_hang, ma_san_pham, so_luong, gia_tai_thoi_diem)
                VALUES (:mdh, :msp, :sl, :gt)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':mdh' => $data['ma_don_hang'],
            ':msp' => $data['ma_san_pham'],
            ':sl'  => $data['so_luong'],
            ':gt'  => $data['gia_tai_thoi_diem'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Xóa tất cả chi tiết đơn hàng theo mã đơn hàng
     * @param int $ma_don_hang
     */
    public function deleteByOrder(int $ma_don_hang): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM chi_tiet_don_hang 
             WHERE ma_don_hang = :mdh"
        );
        $stmt->execute([':mdh' => $ma_don_hang]);
    }

    /**
     * Lấy các mục trong giỏ hàng của người dùng (giả lập giỏ dùng chung với chi tiết)
     * @param int $ma_nguoi_dung
     * @return array
     */
    public function findCartByUser(int $ma_nguoi_dung): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ct.*, sp.ten_san_pham, sp.hinh_anh 
             FROM gio_hang gh
             JOIN chi_tiet_don_hang ct 
               ON ct.ma_don_hang = gh.ma_gio_hang
             JOIN san_pham sp 
               ON sp.ma_san_pham = ct.ma_san_pham
             WHERE gh.ma_nguoi_dung = :mnd"
        );
        $stmt->execute([':mnd' => $ma_nguoi_dung]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa sạch giỏ hàng (chi tiết) của người dùng
     * @param int $ma_nguoi_dung
     */
    public function clearCart(int $ma_nguoi_dung): void
    {
        // Giả sử bảng gio_hang lưu ma_gio_hang = ma_don_hang tạm
        $stmt = $this->pdo->prepare(
            "DELETE ct
             FROM chi_tiet_don_hang ct
             JOIN gio_hang gh 
               ON gh.ma_gio_hang = ct.ma_don_hang
             WHERE gh.ma_nguoi_dung = :mnd"
        );
        $stmt->execute([':mnd' => $ma_nguoi_dung]);
    }
}
