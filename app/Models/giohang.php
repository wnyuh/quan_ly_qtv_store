<?php
// app/Models/giohang.php

class giohang
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
     * Lấy tất cả mục trong giỏ hàng của một người dùng
     * @param int $ma_nguoi_dung
     * @return array
     */
    public function allByUser($ma_nguoi_dung)
    {
        $stmt = $this->pdo->prepare(
            "SELECT gh.ma_gio_hang, gh.so_luong, sp.*
             FROM gio_hang gh
             JOIN san_pham sp ON gh.ma_san_pham = sp.ma_san_pham
             WHERE gh.ma_nguoi_dung = :mnd"
        );
        $stmt->execute([':mnd' => (int)$ma_nguoi_dung]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm một mục giỏ hàng theo ID giỏ
     * @param int $ma_gio_hang
     * @return array|null
     */
    public function find($ma_gio_hang)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM gio_hang WHERE ma_gio_hang = :mgh LIMIT 1"
        );
        $stmt->execute([':mgh' => (int)$ma_gio_hang]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Tìm mục giỏ hàng của user theo sản phẩm (nếu đã có)
     * @param int $ma_nguoi_dung
     * @param int $ma_san_pham
     * @return array|null
     */
    public function findByUserAndProduct($ma_nguoi_dung, $ma_san_pham)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * 
             FROM gio_hang 
             WHERE ma_nguoi_dung = :mnd 
               AND ma_san_pham  = :msp
             LIMIT 1"
        );
        $stmt->execute([
            ':mnd' => (int)$ma_nguoi_dung,
            ':msp' => (int)$ma_san_pham
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Thêm hoặc cập nhật số lượng cho một mục trong giỏ
     * Nếu sản phẩm đã tồn tại trong giỏ thì cộng dồn số lượng
     * @param int $ma_nguoi_dung
     * @param int $ma_san_pham
     * @param int $so_luong
     */
    public function addItem($ma_nguoi_dung, $ma_san_pham, $so_luong)
    {
        $existing = $this->findByUserAndProduct($ma_nguoi_dung, $ma_san_pham);
        if ($existing) {
            $newQty = $existing['so_luong'] + (int)$so_luong;
            $stmt = $this->pdo->prepare(
                "UPDATE gio_hang 
                 SET so_luong = :qty 
                 WHERE ma_gio_hang = :mgh"
            );
            $stmt->execute([
                ':qty' => $newQty,
                ':mgh' => $existing['ma_gio_hang']
            ]);
        } else {
            $stmt = $this->pdo->prepare(
                "INSERT INTO gio_hang (ma_nguoi_dung, ma_san_pham, so_luong)
                 VALUES (:mnd, :msp, :qty)"
            );
            $stmt->execute([
                ':mnd' => (int)$ma_nguoi_dung,
                ':msp' => (int)$ma_san_pham,
                ':qty' => (int)$so_luong
            ]);
        }
    }

    /**
     * Cập nhật số lượng cho một mục giỏ hàng
     * @param int $ma_gio_hang
     * @param int $so_luong
     */
    public function updateQuantity($ma_gio_hang, $so_luong)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE gio_hang 
             SET so_luong = :qty 
             WHERE ma_gio_hang = :mgh"
        );
        $stmt->execute([
            ':qty' => (int)$so_luong,
            ':mgh' => (int)$ma_gio_hang
        ]);
    }

    /**
     * Xóa một mục khỏi giỏ hàng
     * @param int $ma_gio_hang
     */
    public function delete($ma_gio_hang)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM gio_hang WHERE ma_gio_hang = :mgh"
        );
        $stmt->execute([':mgh' => (int)$ma_gio_hang]);
    }

    /**
     * Xóa tất cả mục giỏ hàng của một người dùng
     * @param int $ma_nguoi_dung
     */
    public function clearByUser($ma_nguoi_dung)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM gio_hang WHERE ma_nguoi_dung = :mnd"
        );
        $stmt->execute([':mnd' => (int)$ma_nguoi_dung]);
    }
}
