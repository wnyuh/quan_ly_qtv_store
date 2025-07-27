<?php
// app/Views/admin/qly_sanpham/index.php
?>
<div class="admin-page">
    <h1>Quản lý Sản phẩm</h1>
    <table class="table">
        <thead>
        <tr>
            <th>STT</th>
            <th>Mã</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($products as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($p['ma_san_pham']) ?></td>
                <td><?= htmlspecialchars($p['ten_san_pham']) ?></td>
                <td><?= number_format($p['gia_ban'],0,',','.') ?></td>
                <td>
                    <a href="index.php?url=qly_sanpham/edit&id=<?= $p['ma_san_pham'] ?>">Sửa</a>
                    <a href="index.php?url=qly_sanpham/delete&id=<?= $p['ma_san_pham'] ?>"
                       onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="admin-toolbar">
        <!-- Dropdown filter -->
        <select id="filter-by" class="toolbar-filter">
            <option value="ma_san_pham">Tìm theo mã</option>
            <option value="ten_san_pham">Tìm theo tên</option>
            <!-- ... -->
        </select>

        <!-- Input tìm kiếm -->
        <input
                type="text"
                id="search-input"
                class="toolbar-search"
                placeholder="Tìm kiếm..."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                onkeypress="if(event.key==='Enter') document.getElementById('search-btn').click()"
        >

        <!-- Nút thêm sản phẩm -->
        <a href="index.php?url=qly_sanpham/create" class="btn toolbar-add">
            <span class="icon">＋</span> Thêm sản phẩm
        </a>
    </div>
</div>
