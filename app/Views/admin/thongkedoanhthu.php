<?php
// app/Views/admin/thongkedoanhthu.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container admin-stats">
    <h2 class="stats-title">Thống kê doanh thu</h2>

    <div class="stats-card">
        <p class="stats-item">
            <strong>Tổng doanh thu:</strong>
            <?= number_format($doanh_thu, 0, ',', '.') ?>₫
        </p>

        <h3 class="stats-subtitle">Trạng thái đơn hàng</h3>
        <ul class="stats-list">
            <?php foreach ($statusCounts as $s): ?>
                <li>
                    <?= htmlspecialchars($s['trang_thai']) ?>:
                    <span class="stats-badge"><?= $s['cnt'] ?> đơn</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
