<h2 class="text-4xl font-extrabold text-center mb-10 text-yellow-400 drop-shadow-lg">Đăng nhập</h2>

<?php if (!empty($error)): ?>
    <div class="max-w-md mx-auto mb-8 p-5 bg-red-700 bg-opacity-90 text-white rounded-xl text-center font-semibold shadow-lg animate-pulse">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="/dang-nhap" class="max-w-md mx-auto bg-gray-900 bg-opacity-90 p-10 rounded-3xl shadow-2xl ring-1 ring-yellow-400 ring-opacity-40 hover:ring-opacity-70 transition-all duration-300">
    <div class="mb-8">
        <label for="email" class="block text-yellow-300 font-semibold mb-3 text-lg">Email</label>
        <input id="email" name="email" type="email" required
            class="w-full px-5 py-4 rounded-xl bg-gray-800 border border-yellow-400 text-yellow-100 placeholder-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-400 focus:ring-opacity-60 transition duration-300"
            placeholder="Nhập email của bạn" />
    </div>
    <div class="mb-10">
        <label for="mat_khau" class="block text-yellow-300 font-semibold mb-3 text-lg">Mật khẩu</label>
        <input id="mat_khau" name="mat_khau" type="password" required
            class="w-full px-5 py-4 rounded-xl bg-gray-800 border border-yellow-400 text-yellow-100 placeholder-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-400 focus:ring-opacity-60 transition duration-300"
            placeholder="Nhập mật khẩu của bạn" />
    </div>
    <button type="submit"
        class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-black font-extrabold py-4 rounded-3xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 active:scale-95 transform transition duration-200">
        Đăng nhập
    </button>
</form>

<div class="max-w-md mx-auto mt-6 text-center text-yellow-300 font-semibold text-lg">
    <p>Bạn chưa có tài khoản?
        <a href="/dang-ky" class="text-yellow-400 font-bold hover:text-yellow-600 underline transition duration-200">Đăng ký ngay</a>
    </p>
</div>