<?php
// app/Models/diachi.php

class diachi
{
    /** @var PDO */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả địa chỉ (hoặc của một người dùng cụ thể nếu truyền $maNguoiDung)
     * @param int|null $maNguoiDung
     * @return array
     */
    public function all(int $maNguoiDung = null): array
    {
        if ($maNguoiDung !== null) {
            $stmt = $this->pdo->prepare("
                SELECT * 
                FROM dia_chi_giao_hang 
                WHERE ma_nguoi_dung = :ma_nd 
                ORDER BY mac_dia_chi DESC
            ");
            $stmt->execute([':ma_nd' => $maNguoiDung]);
        } else {
            $stmt = $this->pdo->query("
                SELECT * 
                FROM dia_chi_giao_hang 
                ORDER BY mac_dia_chi DESC
            ");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm một địa chỉ theo ID
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM dia_chi_giao_hang 
            WHERE mac_dia_chi = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Tạo mới địa chỉ
     * @param int $maNguoiDung
     * @param string $tenNhan
     * @param string $soNha
     * @param string $phuong
     * @param string $quan
     * @param string $tinh
     * @return int ID vừa chèn
     */
    public function create(int $maNguoiDung, string $tenNhan, string $soNha, string $phuong, string $quan, string $tinh): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO dia_chi_giao_hang
              (ma_nguoi_dung, ten_nhan, so_nha, phuong, quan, tinh, ngay_tao)
            VALUES
              (:ma_nd, :ten_nhan, :so_nha, :phuong, :quan, :tinh, NOW())
        ");
        $stmt->execute([
            ':ma_nd'    => $maNguoiDung,
            ':ten_nhan' => $tenNhan,
            ':so_nha'   => $soNha,
            ':phuong'   => $phuong,
            ':quan'     => $quan,
            ':tinh'     => $tinh,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật một địa chỉ
     * @param int $id
     * @param string $tenNhan
     * @param string $soNha
     * @param string $phuong
     * @param string $quan
     * @param string $tinh
     * @return bool
     */
    public function update(int $id, string $tenNhan, string $soNha, string $phuong, string $quan, string $tinh): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE dia_chi_giao_hang
               SET ten_nhan   = :ten_nhan,
                   so_nha     = :so_nha,
                   phuong     = :phuong,
                   quan       = :quan,
                   tinh       = :tinh,
                   ngay_cap_nhat = NOW()
             WHERE mac_dia_chi = :id
        ");
        return $stmt->execute([
            ':ten_nhan' => $tenNhan,
            ':so_nha'   => $soNha,
            ':phuong'   => $phuong,
            ':quan'     => $quan,
            ':tinh'     => $tinh,
            ':id'       => $id,
        ]);
    }

    /**
     * Xóa một địa chỉ theo ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE 
            FROM dia_chi_giao_hang 
            WHERE mac_dia_chi = :id
        ");
        return $stmt->execute([':id' => $id]);
    }
}
