<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Báo cáo Doanh thu</h1>
    </div>

    <form method="GET" class="flex space-x-4 items-end">
        <div>
            <label for="from" class="block text-sm font-medium">Từ ngày</label>
            <input type="date" id="from" name="from"
                   value="<?= htmlspecialchars($from) ?>"
                   class="input" />
        </div>
        <div>
            <label for="to" class="block text-sm font-medium">Đến ngày</label>
            <input type="date" id="to" name="to"
                   value="<?= htmlspecialchars($to) ?>"
                   class="input" />
        </div>
        <div>
            <label for="unit" class="block text-sm font-medium">Thống kê theo</label>
            <select id="unit" name="unit" class="select w-[180px]" onchange="this.form.submit()">
                <option value="day"   <?= ($unit === 'day')   ? 'selected' : '' ?>>Ngày</option>
                <option value="week"  <?= ($unit === 'week')  ? 'selected' : '' ?>>Tuần</option>
                <option value="month" <?= ($unit === 'month') ? 'selected' : '' ?>>Tháng</option>
                <option value="year"  <?= ($unit === 'year')  ? 'selected' : '' ?>>Năm</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn">Xem báo cáo</button>
        </div>
    </form>

    <?php if (!empty($rows)): ?>
        <div class="overflow-x-auto mt-6">
            <table class="w-full">
                <thead>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left">Đơn vị</th>
                    <th class="px-4 py-2 text-right">Doanh thu (₫)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr class="border-b hover:bg-muted/50">
                        <td class="px-4 py-2"><?= htmlspecialchars($r['donVi']) ?></td>
                        <td class="px-4 py-2 text-right">
                            <?= number_format($r['doanhThu'], 0, ',', '.') ?> ₫
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="px-4 py-2 font-medium">Tổng</td>
                    <td class="px-4 py-2 text-right font-bold">
                        <?= number_format($totalAll, 0, ',', '.') ?> ₫
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted-foreground mt-6">
            Không có dữ liệu doanh thu trong khoảng đã chọn.
        </p>
    <?php endif; ?>
</div>
