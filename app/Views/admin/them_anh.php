<?php include __DIR__ . '/menu_chung.php'; ?>

<h2>Quản lý Ảnh Slider</h2>
<form action="index.php?url=anh/store" method="post" enctype="multipart/form-data">
    <div>
        <label>Chọn ảnh: <input type="file" name="hinh_anh" required></label>
    </div>
    <div>
        <label>Thứ tự: <input type="number" name="thu_tu" value="0" style="width:60px"></label>
    </div>
    <button type="submit">Tải lên</button>
</form>

<hr>

<h3>Danh sách ảnh hiện có</h3>
<div class="admin-images">
    <?php foreach($images as $img): ?>
        <div class="thumb">
            <img src="../images/<?= htmlspecialchars($img['hinh_anh']) ?>" width="200">
            <p>Thứ tự: <?= $img['thu_tu'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .admin-images { display:flex; flex-wrap:wrap; gap:20px; }
    .thumb { text-align:center; }
</style>
