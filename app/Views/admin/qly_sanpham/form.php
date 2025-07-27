<?php
// app/Views/admin/qly_sanpham/form.php
// Controller đã truyền vào mảng $dms
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm/Sửa Sản phẩm</title>
    <link rel="stylesheet" href="/phan_mem_quan_ly_ban_dien_thoai_iphone/public/css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: #333;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
        }
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        .form-group {
            display: flex;
            margin-bottom: 12px;
        }
        .form-group label {
            flex: 0 0 140px;
            align-self: center;
        }
        .form-group input,
        .form-group select {
            flex: 1;
            padding: 6px 8px;
            border: 1px solid #555;
            border-radius: 4px;
            background: #444;
            color: #fff;
        }
        .form-actions {
            text-align: right;
            margin-top: 20px;
        }
        .form-actions button,
        .form-actions a {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
            margin-left: 8px;
        }
        .btn-submit { background: #28a745; }
        .btn-cancel { background: #6c757d; }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Thêm Sản phẩm mới</h1>
    <form action="index.php?url=qly_sanpham/store" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="ten_san_pham">Tên sản phẩm:</label>
            <input type="text" id="ten_san_pham" name="ten_san_pham" required>
        </div>
        <div class="form-group">
            <label for="ma_danh_muc">Danh mục:</label>
            <select id="ma_danh_muc" name="ma_danh_muc" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach($dms as $dm): ?>
                    <option value="<?= $dm['ma_danh_muc'] ?>">
                        <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hinh_anh">Hình ảnh:</label>
            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
        </div>
        <div class="form-group">
            <label for="gia_ban">Giá bán:</label>
            <input type="number" id="gia_ban" name="gia_ban" step="0.01" required>
        </div>

        <div class="form-actions">
            <a href="index.php?url=qly_sanpham/index" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-submit">Thêm</button>
        </div>
    </form>
</div>

</body>
</html>
