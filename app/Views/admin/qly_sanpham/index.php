<?php
// app/Views/admin/qly_sanpham/index.php
?>

<div class="container mx-auto p-6 max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý Sản phẩm</h1>
        <a href="index.php?url=qly_sanpham/create" 
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
            <span class="text-lg">+</span>
            Thêm sản phẩm
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <select id="filter-by" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="ma_san_pham">Tìm theo mã</option>
                <option value="ten_san_pham">Tìm theo tên</option>
            </select>
            
            <div class="flex-1">
                <input type="text"
                       id="search-input"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Tìm kiếm sản phẩm..."
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                       onkeypress="if(event.key==='Enter') document.getElementById('search-btn').click()">
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá bán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach($products as $i => $p): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $i+1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($p['ma_san_pham']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($p['ten_san_pham']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"><?= number_format($p['gia_ban'],0,',','.') ?>₫</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="index.php?url=qly_sanpham/edit&id=<?= $p['ma_san_pham'] ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors">Sửa</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="index.php?url=qly_sanpham/delete&id=<?= $p['ma_san_pham'] ?>"
                                       class="text-red-600 hover:text-red-900 transition-colors"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
