<div class="container mx-auto p-6 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý Đơn hàng</h1>
        <div class="flex items-center gap-4">
            <select class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tất cả trạng thái</option>
                <option value="cho_xu_ly">Chờ xử lý</option>
                <option value="dang_giao">Đang giao</option>
                <option value="hoan_thanh">Hoàn thành</option>
            </select>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($don_hangs)): ?>
                        <?php foreach ($don_hangs as $dh): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= htmlspecialchars($dh['ma_don_hang']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($dh['ten_khach_hang'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= number_format($dh['tong_tien'] ?? 0, 0, ',', '.') ?>₫
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($dh['trang_thai']) {
                                        case 'cho_xu_ly':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Chờ xử lý';
                                            break;
                                        case 'dang_giao':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'Đang giao';
                                            break;
                                        case 'hoan_thanh':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Hoàn thành';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = $dh['trang_thai'];
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($dh['ngay_dat'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" class="flex items-center gap-2">
                                        <input type="hidden" name="ma_don_hang" value="<?= htmlspecialchars($dh['ma_don_hang']) ?>">
                                        <select name="trang_thai" 
                                                class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                                            <option value="cho_xu_ly" <?= $dh['trang_thai'] === 'cho_xu_ly' ? 'selected' : '' ?>>Chờ xử lý</option>
                                            <option value="dang_giao" <?= $dh['trang_thai'] === 'dang_giao' ? 'selected' : '' ?>>Đang giao</option>
                                            <option value="hoan_thanh" <?= $dh['trang_thai'] === 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                                        </select>
                                        <button type="submit" 
                                                name="cap_nhat_trang_thai"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                            Cập nhật
                                        </button>
                                        <a href="index.php?url=qly_donhang/chi_tiet&id=<?= $dh['ma_don_hang'] ?>" 
                                           class="text-gray-600 hover:text-gray-900 transition-colors">
                                            Chi tiết
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M15 13l-3 3m0 0l-3-3m3 3V8"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Chưa có đơn hàng nào</p>
                                    <p class="text-sm">Các đơn hàng mới sẽ xuất hiện ở đây</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>