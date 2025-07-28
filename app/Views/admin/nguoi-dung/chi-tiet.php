<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold"><?= htmlspecialchars($user->getHoTen()) ?></h1>
            <p class="text-muted-foreground">ID: <?= $user->getId() ?></p>
        </div>
        <div class="space-x-2">
            <a href="/admin/nguoi-dung/sua/<?= $user->getId() ?>" class="btn-outline">Sửa</a>
            <a href="/admin/nguoi-dung" class="btn-outline">Quay lại</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card p-4">
            <h3 class="font-medium">Thông tin cơ bản</h3>
            <ul class="mt-2 space-y-2 text-sm">
                <li><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></li>
                <li><strong>SĐT:</strong> <?= htmlspecialchars($user->getSoDienThoai() ?? '-') ?></li>
                <li><strong>Ngày sinh:</strong> <?= $user->getNgaySinh()?->format('d/m/Y') ?? '-' ?></li>
                <li><strong>Ngày tạo:</strong> <?= $user->getNgayTao()->format('d/m/Y H:i') ?></li>
                <li><strong>Cập nhật:</strong> <?= $user->getNgayCapNhat()->format('d/m/Y H:i') ?></li>
            </ul>
        </div>

        <div class="card p-4">
            <h3 class="font-medium">Đơn hàng & Giỏ hàng</h3>
            <ul class="mt-2 space-y-2 text-sm">
                <li>Số đơn hàng: <?= count($user->getDonHangs()) ?></li>
                <li>Số giỏ hàng: <?= count($user->getGioHangs()) ?></li>
                <li>Số địa chỉ: <?= count($user->getDiaChis()) ?></li>
            </ul>
        </div>
    </div>
</div>
