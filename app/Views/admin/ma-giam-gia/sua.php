<?php if (isset($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<form method="POST" class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block font-medium">Mã giảm giá</label>
            <p class="text-foreground"><?= htmlspecialchars($ma->getMaGiamGia()) ?></p>
        </div>
        <div>
            <label for="ten" class="block font-medium">Tên *</label>
            <input type="text" id="ten" name="ten" required class="input" value="<?= htmlspecialchars($ma->getTen()) ?>" />
        </div>
        <div>
            <label for="loai_giam_gia" class="block font-medium">Loại giảm *</label>
            <select id="loai_giam_gia" name="loai_giam_gia" class="select">
                <option value="phan_tram" <?= $ma->getLoaiGiamGia()==='phan_tram'?'selected':'' ?>>Phần trăm</option>
                <option value="so_tien"  <?= $ma->getLoaiGiamGia()==='so_tien' ?'selected':'' ?>>Số tiền</option>
            </select>
        </div>
        <div>
            <label for="gia_tri_giam" class="block font-medium">Giá trị giảm *</label>
            <input type="number" id="gia_tri_giam" name="gia_tri_giam" min="0" step="0.01" class="input" value="<?= $ma->getGiaTriGiam() ?>" />
        </div>
        <div>
            <label for="gia_tri_don_hang_toi_thieu" class="block font-medium">Đơn hàng tối thiểu</label>
            <input type="number" id="gia_tri_don_hang_toi_thieu" name="gia_tri_don_hang_toi_thieu" min="0" step="0.01" class="input" value="<?= $ma->getGiaTriDonHangToiThieu() ?>" />
        </div>
        <div>
            <label for="so_luong_toi_da" class="block font-medium">Số lượng tối đa</label>
            <input type="number" id="so_luong_toi_da" name="so_luong_toi_da" min="1" class="input" value="<?= $ma->getSoLuongToiDa() ?>" />
        </div>
        <div>
            <label for="ngay_bat_dau" class="block font-medium">Ngày bắt đầu *</label>
            <input type="datetime-local" id="ngay_bat_dau" name="ngay_bat_dau" required class="input"
                   value="<?= $ma->getNgayBatDau()->format('Y-m-d\TH:i') ?>" />
        </div>
        <div>
            <label for="ngay_ket_thuc" class="block font-medium">Ngày kết thúc *</label>
            <input type="datetime-local" id="ngay_ket_thuc" name="ngay_ket_thuc" required class="input"
                   value="<?= $ma->getNgayKetThuc()->format('Y-m-d\TH:i') ?>" />
        </div>
        <div class="col-span-2 flex items-center">
            <input type="checkbox" id="kich_hoat" name="kich_hoat" <?= $ma->isKichHoat()?'checked':'' ?> />
            <label for="kich_hoat" class="ml-2">Kích hoạt</label>
        </div>
    </div>
    <div>
        <button type="submit" class="btn w-full">Lưu</button>
    </div>
</form>
