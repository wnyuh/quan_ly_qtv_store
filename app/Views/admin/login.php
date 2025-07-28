<div class="min-h-screen flex items-center justify-center bg-background">
    <div class="card w-full max-w-md">
        <header>
            <h2>Admin Login</h2>
            <p>Đăng nhập vào trang quản trị</p>
        </header>
        
        <section>
            <?php if (isset($error)): ?>
                <div class="bg-destructive/10 border border-destructive/20 text-destructive px-4 py-3 rounded-lg mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="form grid gap-6">
                <div class="grid gap-2">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" required placeholder="Nhập tên đăng nhập">
                </div>
                
                <div class="grid gap-2">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
                </div>
            </form>
        </section>
        
        <footer class="flex flex-col items-center gap-2">
            <button type="submit" form="login-form" class="btn w-full">Đăng nhập</button>
        </footer>
    </div>
</div>

<script>
// Connect the form with the button
document.querySelector('.btn').onclick = function() {
    document.querySelector('form').submit();
};
</script>