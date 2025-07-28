<h2 class="text-2xl font-bold mb-6">Thanh toán đơn hàng</h2>

<!-- Bảng sản phẩm -->
<div class="mb-6">
    <!-- Table sản phẩm (có thể dùng lại component table của cart) -->
</div>

<!-- Thông tin khách hàng -->
<form method="post" action="/gio-hang/dat-hang" class="space-y-4 max-w-lg">
    <h3 class="font-semibold mb-2">Thông tin nhận hàng</h3>
    <div>
        <label>Họ và tên</label>
        <input name="name" required value="<?= htmlspecialchars($user?->getTen() ?? '') ?>" class="input" />
    </div>
    <div>
        <label>Số điện thoại</label>
        <input name="phone" required value="<?= htmlspecialchars($user?->getSoDienThoai() ?? '') ?>" class="input" />
    </div>
    <div>
        <label>Địa chỉ nhận hàng</label>
        <input name="address" required value="<?= htmlspecialchars($user?->getDiaChi() ?? '') ?>" class="input" />
    </div>
    <div>
        <label>Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($user?->getEmail() ?? '') ?>" class="input" />
    </div>

    <h3 class="font-semibold mt-6 mb-2">Phương thức thanh toán</h3>
    <div class="flex gap-4">
        <label><input type="radio" name="payment" value="cod" checked /> Thanh toán khi nhận hàng</label>
        <label><input type="radio" name="payment" value="bank" /> Chuyển khoản</label>
    </div>

    <!-- Tổng kết -->
    <div class="bg-yellow-50 p-4 rounded-xl mt-4">
        <div class="flex justify-between font-semibold">
            <span>Tạm tính:</span>
            <span><?= number_format($tong, 0, ',', '.') ?> ₫</span>
        </div>
        <!-- Nếu có phí ship thì cộng ở đây -->
    </div>

    <button type="submit" class="w-full rounded-xl mt-4 bg-green-600 hover:bg-green-700 text-white py-3 font-bold text-lg shadow">
        Xác nhận đặt hàng
    </button>
</form>