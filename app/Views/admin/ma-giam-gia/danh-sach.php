<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Danh sách Mã giảm giá</h1>
        <a href="/admin/ma-giam-gia/them" class="btn">Thêm mới</a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
            <tr class="border-b">
                <th class="px-4 py-2 text-left">Mã</th>
                <th class="px-4 py-2 text-left">Tên</th>
                <th class="px-4 py-2 text-left">Loại</th>
                <th class="px-4 py-2 text-left">Giá trị</th>
                <th class="px-4 py-2 text-left">Trạng thái</th>
                <th class="px-4 py-2 text-right">Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $ma): ?>
                <tr class="border-b hover:bg-muted/50">
                    <td class="px-4 py-2"><?= htmlspecialchars($ma->getMaGiamGia()) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($ma->getTen()) ?></td>
                    <td class="px-4 py-2"><?= $ma->getLoaiGiamGia()==='phan_tram' ? 'Phần trăm' : 'Số tiền' ?></td>
                    <td class="px-4 py-2"><?= $ma->layGiaTriGiamDinhDang() ?></td>
                    <td class="px-4 py-2"><?= $ma->layTrangThaiText() ?></td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="/admin/ma-giam-gia/chi-tiet/<?= $ma->getId() ?>" class="btn-outline">Xem</a>
                        <a href="/admin/ma-giam-gia/sua/<?= $ma->getId() ?>" class="btn-outline">Sửa</a>
                        <form method="POST" action="/admin/ma-giam-gia/xoa/<?= $ma->getId() ?>" style="display:inline;" onsubmit="return confirm('Xác nhận xóa?');">
                            <button type="submit" class="btn-outline text-red-600">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <!-- pagination -->
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?= $p ?>" class="px-3 py-1 <?= $currentPage==$p?'bg-primary text-white':'bg-muted/50' ?> rounded-lg"><?= $p ?></a>
        <?php endfor; ?>
    </div>
</div>
