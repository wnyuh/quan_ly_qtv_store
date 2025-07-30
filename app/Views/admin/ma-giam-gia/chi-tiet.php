<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Chi tiết Mã giảm giá</h1>
        <div class="space-x-2">
            <a href="/admin/ma-giam-gia/sua/<?= $ma->getId() ?>" class="btn-outline">Sửa</a>
            <a href="/admin/ma-giam-gia" class="btn-outline">Quay lại</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="font-medium">Mã giảm giá:</label>
            <p><?= htmlspecialchars($ma->getMaGiamGia()) ?></p>
        </div>
        <div>
            <label class="font-medium">Tên:</label>
            <p><?= htmlspecialchars($ma->getTen()) ?></p>
        </div>
        <div>
            <label class="font-medium">Loại giảm:</label>
            <p><?= $ma->getLoaiGiamGia()==='phan_tram'?'Phần trăm':'Số tiền' ?></p>
        </div>
        <div>
            <label class="font-medium">Giá trị giảm:</label>
            <p><?= $ma->layGiaTriGiamDinhDang() ?></p>
        </div>
        <div>
            <label class="font-medium">Đơn hàng tối thiểu:</label>
            <p><?= $ma->getGiaTriDonHangToiThieu()? number_format($ma->getGiaTriDonHangToiThieu(),0,',','.') . ' ₫':'Không yêu cầu' ?></p>
        </div>
        <div>
            <label class="font-medium">Số lượng tối đa:</label>
            <p><?= $ma->getSoLuongToiDa() ?? 'Không giới hạn' ?></p>
        </div>
        <div>
            <label class="font-medium">Ngày bắt đầu:</label>
            <p><?= $ma->getNgayBatDau()->format('d/m/Y H:i') ?></p>
        </div>
        <div>
            <label class="font-medium">Ngày kết thúc:</label>
            <p><?= $ma->getNgayKetThuc()->format('d/m/Y H:i') ?></p>
        </div>
        <div>
            <label class="font-medium">Trạng thái:</label>
            <p><?= $ma->layTrangThaiText() ?></p>
        </div>
    </div>