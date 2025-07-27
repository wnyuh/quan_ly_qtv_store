<?php
// app/Views/admin/qly_danhmuc.php

// Kiểm tra đã login admin
if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: index.php?url=admin/login');
    exit;
}
?>

<div class="container mx-auto p-6 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý Danh mục</h1>
        <a href="index.php?url=qly_danhmuc/them" 
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
            <span class="text-lg">+</span>
            Thêm danh mục mới
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($dms)): ?>
                        <?php foreach($dms as $dm): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $dm['ma_danh_muc'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($dm['ngay_tao'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="index.php?url=qly_danhmuc/sua&id=<?= $dm['ma_danh_muc'] ?>" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">Sửa</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="index.php?url=qly_danhmuc/xoa&id=<?= $dm['ma_danh_muc'] ?>"
                                           class="text-red-600 hover:text-red-900 transition-colors"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Chưa có danh mục nào</p>
                                    <p class="text-sm">Bắt đầu bằng cách tạo danh mục đầu tiên</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
