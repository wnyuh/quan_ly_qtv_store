<?php
// app/Views/admin/thongkedoanhthu.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container mx-auto p-6 max-w-4xl">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Thống kê doanh thu</h2>

    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <div class="mb-6">
            <div class="text-sm text-gray-600 mb-2">Tổng doanh thu</div>
            <div class="text-2xl font-bold text-blue-600">
                <?= number_format($doanh_thu, 0, ',', '.') ?>₫
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái đơn hàng</h3>
            <div class="space-y-3">
                <?php foreach ($statusCounts as $s): ?>
                    <div class="flex items-center justify-between p-3 rounded-md bg-gray-50">
                        <span class="text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($s['trang_thai']) ?>
                        </span>
                        <span class="inline-flex items-center rounded-full bg-blue-600 px-2.5 py-0.5 text-xs font-medium text-white">
                            <?= $s['cnt'] ?> đơn
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php //include __DIR__ . '/../layouts/footer.php'; ?>
