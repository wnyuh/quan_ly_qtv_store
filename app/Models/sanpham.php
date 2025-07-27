<?php
// app/Models/sanpham.php

class sanpham
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
     * Lấy tất cả sản phẩm, nếu có mã danh mục thì lọc
     * @param int|null $ma_dm
     * @return array
     */
    public function all($ma_dm = null)
    {
        if ($ma_dm) {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM san_pham 
                 WHERE ma_danh_muc = :mdm 
                 ORDER BY ngay_tao DESC"
            );
            $stmt->execute([':mdm' => (int)$ma_dm]);
        } else {
            $stmt = $this->pdo->query(
                "SELECT * FROM san_pham 
                 ORDER BY ngay_tao DESC"
            );
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Lấy một sản phẩm theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM san_pham 
             WHERE ma_san_pham = :id 
             LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Lấy $n sản phẩm mới nhất
     * @param int $n
     * @return array
     */
    public function latest($n)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM san_pham 
             ORDER BY ngay_tao DESC 
             LIMIT :n"
        );
        $stmt->bindValue(':n', (int)$n, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả danh mục
     * @return array
     */
    public function allCategories()
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM danh_muc 
             ORDER BY ngay_tao DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sản phẩm liên quan cùng danh mục, trừ sản phẩm hiện tại
     * @param int $ma_dm
     * @param int $excludeId
     * @param int $limit
     * @return array
     */
    public function related($ma_dm, $excludeId, $limit = 4)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM san_pham 
             WHERE ma_danh_muc = :mdm 
               AND ma_san_pham <> :eid 
             ORDER BY ngay_tao DESC 
             LIMIT :lim"
        );
        $stmt->bindValue(':mdm', (int)$ma_dm, PDO::PARAM_INT);
        $stmt->bindValue(':eid', (int)$excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm sản phẩm mới
     * @param array $data  (ten_san_pham, gia_ban, hinh_anh, mo_ta, ma_danh_muc)
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $sql = "INSERT INTO san_pham 
                (ten_san_pham, gia_ban, hinh_anh, mo_ta, ma_danh_muc) 
                VALUES (:ten, :gia, :hinh, :mo, :mdm)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ten'  => trim($data['ten_san_pham']),
            ':gia'  => (float)$data['gia_ban'],
            ':hinh' => trim($data['hinh_anh']),
            ':mo'   => trim($data['mo_ta']),
            ':mdm'  => (int)$data['ma_danh_muc'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật sản phẩm
     * @param int   $id
     * @param array $data  có thể gồm ten_san_pham, gia_ban, hinh_anh, mo_ta, ma_danh_muc
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (isset($data['ten_san_pham'])) {
            $fields[]           = "ten_san_pham = :ten";
            $params[':ten']     = trim($data['ten_san_pham']);
        }
        if (isset($data['gia_ban'])) {
            $fields[]           = "gia_ban = :gia";
            $params[':gia']     = (float)$data['gia_ban'];
        }
        if (isset($data['hinh_anh'])) {
            $fields[]           = "hinh_anh = :hinh";
            $params[':hinh']    = trim($data['hinh_anh']);
        }
        if (isset($data['mo_ta'])) {
            $fields[]           = "mo_ta = :mo";
            $params[':mo']      = trim($data['mo_ta']);
        }
        if (isset($data['ma_danh_muc'])) {
            $fields[]           = "ma_danh_muc = :mdm";
            $params[':mdm']     = (int)$data['ma_danh_muc'];
        }

        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE san_pham SET " . implode(', ', $fields) . " 
                WHERE ma_san_pham = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa sản phẩm theo ID
     * @param int $id
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM san_pham WHERE ma_san_pham = :id"
        );
        $stmt->execute([':id' => (int)$id]);
    }

    public function getBanners(): array
    {
        $stmt = $this->pdo->query("SELECT hinh_anh FROM anh ORDER BY thu_tu ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
