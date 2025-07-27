<?php
// app/Views/giohang/index.php

// luôn start session nếu cần
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include header từ thư mục layouts
include __DIR__ . '/../layouts/header.php';

// nếu controller truyền xuống biến $items
$items = $items ?? [];
?>
<h1>Giỏ hàng</h1>

<table>
    <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($items as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ten_san_pham']) ?></td>
            <td><?= (int)$row['so_luong'] ?></td>
            <td>
                <a href="index.php?url=giohang/update&ma_san_pham=<?= $row['ma_san_pham'] ?>">Cập nhật</a> |
                <a href="index.php?url=giohang/remove&ma_san_pham=<?= $row['ma_san_pham'] ?>">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="index.php?url=thanhtoan/index">Thanh toán</a></p>

<?php
// include footer
include __DIR__ . '/../layouts/footer.php';
?>
