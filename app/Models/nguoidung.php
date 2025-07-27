<?php
// app/Models/nguoidung.php

class nguoidung
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
     * Lấy tất cả người dùng
     * @return array
     */
    public function all()
    {
        $stmt = $this->pdo->query(
            "SELECT ma_nguoi_dung, ten_dang_nhap, email, vai_tro, ngay_tao
             FROM nguoi_dung
             ORDER BY ngay_tao DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm một người dùng theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ma_nguoi_dung, ten_dang_nhap, email, vai_tro, ngay_tao
             FROM nguoi_dung
             WHERE ma_nguoi_dung = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Tạo người dùng mới
     * @param array $data  (ten_dang_nhap, mat_khau, email, vai_tro)
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $sql = "INSERT INTO nguoi_dung 
                (ten_dang_nhap, mat_khau, email, vai_tro) 
                VALUES (:dn, :mk, :em, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':dn'   => trim($data['ten_dang_nhap']),
            ':mk'   => password_hash($data['mat_khau'], PASSWORD_DEFAULT),
            ':em'   => trim($data['email']),
            ':role' => $data['vai_tro'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật thông tin người dùng
     * @param int   $id
     * @param array $data  (mat_khau?, email?, vai_tro?)
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (!empty($data['mat_khau'])) {
            $fields[]      = "mat_khau = :mk";
            $params[':mk'] = password_hash($data['mat_khau'], PASSWORD_DEFAULT);
        }
        if (!empty($data['email'])) {
            $fields[]       = "email = :em";
            $params[':em']  = trim($data['email']);
        }
        if (!empty($data['vai_tro'])) {
            $fields[]         = "vai_tro = :role";
            $params[':role']  = $data['vai_tro'];
        }

        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE nguoi_dung
                SET " . implode(', ', $fields) . "
                WHERE ma_nguoi_dung = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa người dùng theo ID
     * @param int $id
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM nguoi_dung 
             WHERE ma_nguoi_dung = :id"
        );
        $stmt->execute([':id' => (int)$id]);
    }
}
