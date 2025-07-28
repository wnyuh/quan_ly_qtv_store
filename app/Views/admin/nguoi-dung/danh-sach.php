<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Quản lý Người dùng</h1>
            <p class="text-muted-foreground">Tổng cộng: <?= $total ?> người dùng</p>
        </div>
        <a href="/admin/nguoi-dung/them" class="btn">
            Thêm người dùng
        </a>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(empty($users)): ?>
        <div class="text-center py-8">
            <p class="text-muted-foreground">Chưa có người dùng nào</p>
            <a href="/admin/nguoi-dung/them" class="btn mt-4">Thêm ngay</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="border-b">
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Họ tên</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">SĐT</th>
                    <th class="py-3 px-4 text-right">Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                    <tr class="border-b hover:bg-muted/50">
                        <td class="py-2 px-4"><?= $u->getId() ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($u->getHoTen()) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($u->getSoDienThoai() ?? '-') ?></td>
                        <td class="py-2 px-4 text-right space-x-2">
                            <a href="/admin/nguoi-dung/chi-tiet/<?= $u->getId() ?>" class="btn-icon-outline" title="Chi tiết">🔍</a>
                            <a href="/admin/nguoi-dung/sua/<?= $u->getId() ?>" class="btn-icon-outline" title="Sửa">✏️</a>
                            <form method="POST" action="/admin/nguoi-dung/xoa/<?= $u->getId() ?>" class="inline-block" onsubmit="return confirm('Xác nhận xóa?')">
                                <button type="submit" class="btn-icon-outline text-red-600">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if($totalPages > 1): ?>
            <div class="flex items-center justify-between mt-6">
                <span class="text-sm text-muted-foreground">Trang <?= $currentPage ?> / <?= $totalPages ?></span>
                <div class="space-x-2">
                    <?php if($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage-1 ?>" class="btn-outline text-sm">« Trước</a>
                    <?php endif; ?>
                    <?php if($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage+1 ?>" class="btn-outline text-sm">Sau »</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
