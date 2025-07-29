<h2 class="text-3xl font-bold text-center mb-8 text-white">Đăng ký tài khoản</h2>

<?php if (!empty($error)): ?>
    <div class="max-w-md mx-auto mb-6 p-4 bg-red-600 text-white rounded-lg text-center font-semibold shadow-md">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="max-w-md mx-auto mb-6 p-4 bg-green-600 text-white rounded-lg text-center font-semibold shadow-md">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="post" action="/dang-ky" class="max-w-md mx-auto bg-gray-900 p-8 rounded-lg shadow-lg">
    <div class="mb-6">
        <label for="ho_ten" class="block text-white font-semibold mb-2">Họ và tên:</label>
        <input id="ho_ten" name="ho_ten" type="text" required
            class="w-full px-4 py-3 rounded-md bg-gray-800 border border-gray-700 text-white focus:outline-yellow-400 focus:ring-2 focus:ring-yellow-400 transition" />
    </div>
    <div class="mb-6">
        <label for="email" class="block text-white font-semibold mb-2">Email:</label>
        <input id="email" name="email" type="email" required
            class="w-full px-4 py-3 rounded-md bg-gray-800 border border-gray-700 text-white focus:outline-yellow-400 focus:ring-2 focus:ring-yellow-400 transition" />
    </div>
    <div class="mb-6">
        <label for="mat_khau" class="block text-white font-semibold mb-2">Mật khẩu:</label>
        <input id="mat_khau" name="mat_khau" type="password" required
            class="w-full px-4 py-3 rounded-md bg-gray-800 border border-gray-700 text-white focus:outline-yellow-400 focus:ring-2 focus:ring-yellow-400 transition" />
    </div>
    <div class="mb-6">
        <label for="xac_nhan_mat_khau" class="block text-white font-semibold mb-2">Xác nhận mật khẩu:</label>
        <input id="xac_nhan_mat_khau" name="xac_nhan_mat_khau" type="password" required
            class="w-full px-4 py-3 rounded-md bg-gray-800 border border-gray-700 text-white focus:outline-yellow-400 focus:ring-2 focus:ring-yellow-400 transition" />
    </div>
    <button type="submit"
        class="w-full bg-yellow-400 text-black font-bold py-3 rounded-lg hover:bg-yellow-500 transition-shadow shadow-md">
        Đăng ký
    </button>
</form>