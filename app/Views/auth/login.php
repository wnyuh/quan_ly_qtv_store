<div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-foreground">
            Đăng nhập tài khoản
        </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                <div class="flex items-center space-x-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" x2="9" y1="9" y2="15"/>
                        <line x1="9" x2="15" y1="9" y2="15"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="/dang-nhap" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-foreground">
                    Địa chỉ email
                </label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" required
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="name@example.com" />
                </div>
            </div>

            <div>
                <label for="mat_khau" class="block text-sm font-medium leading-6 text-foreground">
                    Mật khẩu
                </label>
                <div class="mt-2">
                    <input id="mat_khau" name="mat_khau" type="password" required
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Nhập mật khẩu" />
                </div>
            </div>

            <div>
                <button type="submit"
                    class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                    Đăng nhập
                </button>
            </div>
        </form>

        <p class="mt-10 text-center text-sm text-muted-foreground">
            Chưa có tài khoản?
            <a href="/dang-ky" class="font-semibold leading-6 text-primary hover:text-primary/80">
                Đăng ký ngay
            </a>
        </p>
    </div>
</div>