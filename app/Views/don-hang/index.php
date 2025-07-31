<div class="container mx-auto max-w-3xl p-6">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Đơn hàng của tôi</h2>

    <div class="rounded-xl shadow border p-6 space-y-3">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Lịch sử mua hàng</h3>

        <?php if (count($don_hangs) > 0): ?>
            <table class="w-full text-left table-auto">
                <thead>
                    <tr class="border-b ">
                        <th class="py-2 px-4">Mã đơn hàng</th>
                        <th class="py-2 px-4">Ngày mua</th>
                        <th class="py-2 px-4">Tổng tiền</th>
                        <th class="py-2 px-4">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($don_hangs as $don_hang): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?= $don_hang->getId() ?></td>
                            <td class="py-2 px-4"><?= $don_hang->getNgayTao()->format('d/m/Y') ?></td>
                            <td class="py-2 px-4"><?= number_format($don_hang->getTongTien(), 0, ',', '.') ?>₫</td>
                            <td class="py-2 px-4"><?= $don_hang->getTrangThai() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>
</div>