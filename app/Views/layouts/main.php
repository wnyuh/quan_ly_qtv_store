<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <script>
        (() => {
            try {
                const stored = localStorage.getItem('themeMode');
                if (stored ? stored === 'dark' :
                    matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            } catch (_) {}

            const apply = dark => {
                document.documentElement.classList.toggle('dark', dark);
                try {
                    localStorage.setItem('themeMode', dark ? 'dark' : 'light');
                } catch (_) {}
            };

            document.addEventListener('basecoat:theme', (event) => {
                const mode = event.detail?.mode;
                apply(mode === 'dark' ? true :
                    mode === 'light' ? false :
                    !document.documentElement.classList.contains('dark'));
            });
        })();

        function addToCart(variantId, quantity) {
            const formData = new FormData();
            formData.append('bien_the_id', variantId);
            formData.append('so_luong', quantity);

            fetch('/cart/add', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        // **NEW**: Update the cart count display
                        const cartCountElement = document.getElementById('cart-item-count');
                        if (cartCountElement && typeof data.cartItemCount !== 'undefined') {
                            cartCountElement.textContent = data.cartItemCount;
                        }

                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'My Awesome Site'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/basecoat-css@0.2.8/dist/js/all.min.js" defer></script>
</head>

<body class="bg-background text-foreground min-h-screen flex flex-col">
    <header class="border-b bg-card shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <a href="/">
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl md:text-4xl">📱</div>
                        <div>
                            <h1 class="text-base font-bold text-yellow-500 md:text-2xl ">QTV Store</h1>
                            <p class="text-xs text-yellow-500 hidden md:block  ">Cửa hàng điện thoại uy tín</p>
                        </div>
                    </div>
                </a>

                <!-- Main Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Trang Chủ</a>
                    <a href="/tim-kiem-san-pham" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Sản Phẩm</a>
                    <a href="/danh-muc" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Danh Mục</a>
                    <a href="/lien-he" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Liên Hệ</a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <!-- Search Button -->
                    <a href="/tim-kiem-san-pham" class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                    </a>

                    <!-- Cart Button -->
                    <!-- Cart Button (link tới trang giỏ hàng) -->
                    <a href="/gio-hang" class="relative p-2 text-muted-foreground hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L23 6H6" />
                        </svg>
                        <span id="cart-item-count" class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-xs rounded-full w-5 h-5 flex items-center justify-center"><?= $cartItemCount ?? 0 ?></span>
                    </a>


                    <!-- User Menu -->
                    <!-- <div class="flex items-center space-x-2">
                        <a href="/dang-nhap" title="Đăng nhập" class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </a>
                    </div> -->
                    <div class="flex items-center space-x-2">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown-menu">
                                <button type="button" id="user-dropdown-trigger" aria-haspopup="menu" aria-controls="user-dropdown-menu" aria-expanded="false" class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </button>
                                <div id="user-dropdown-popover" data-popover aria-hidden="true" class="min-w-56">
                                    <div role="menu" id="user-dropdown-menu" aria-labelledby="user-dropdown-trigger">
                                        <div role="group" aria-labelledby="account-options">
                                            <div role="heading" id="account-options">Tài Khoản</div>
                                            <a href="/tai-khoan" role="menuitem" class="block">
                                                Thông tin cá nhân
                                            </a>
                                            <a href="/don-hang" role="menuitem" class="block">
                                                Đơn hàng của tôi
                                            </a>
                                        </div>
                                        <hr role="separator" />
                                        <a href="/dang-xuat" role="menuitem" class="block">
                                            Đăng xuất
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/dang-nhap" class="flex items-center space-x-2 bg-primary text-primary-foreground px-3 py-2 rounded-md hover:bg-primary/90 transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                    <polyline points="10,17 15,12 10,7" />
                                    <line x1="15" x2="3" y1="12" y2="12" />
                                </svg>
                                <span class="hidden sm:inline">Đăng Nhập</span>
                            </a>
                        <?php endif; ?>
                    </div>


                    <!-- Theme Toggle -->
                    <button
                        type="button"
                        aria-label="Toggle dark mode"
                        data-side="bottom"
                        onclick="document.dispatchEvent(new CustomEvent('basecoat:theme'))"
                        class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                        <span class="hidden dark:block"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4" />
                                <path d="M12 2v2" />
                                <path d="M12 20v2" />
                                <path d="m4.93 4.93 1.41 1.41" />
                                <path d="m17.66 17.66 1.41 1.41" />
                                <path d="M2 12h2" />
                                <path d="M20 12h2" />
                                <path d="m6.34 17.66-1.41 1.41" />
                                <path d="m19.07 4.93-1.41 1.41" />
                            </svg></span>
                        <span class="block dark:hidden"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                            </svg></span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 text-muted-foreground hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12" />
                            <line x1="4" x2="20" y1="6" y2="6" />
                            <line x1="4" x2="20" y1="18" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php echo $content; ?>
    </main>

    <footer class="border-t bg-card mt-auto ">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="text-xl">📱</div>
                        <h3 class="font-bold text-foreground">QTV Store</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Cửa hàng điện thoại uy tín, chất lượng cao với giá cả phải chăng.Hỗ trợ khách hàng tận tâm - Mua sắm dễ dàng!</p>
                    <div class="flex space-x-3">
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 4.991 3.656 9.128 8.438 9.878v-6.988H7.898V12h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.196 2.238.196v2.46h-1.261c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V21.878C18.344 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452H17.2V14.8c0-1.348-.027-3.082-1.88-3.082-1.881 0-2.169 1.466-2.169 2.982v5.752H9.904V9h3.114v1.561h.044c.433-.82 1.492-1.683 3.071-1.683 3.285 0 3.89 2.162 3.89 4.974v6.6zM5.337 7.433a1.808 1.808 0 1 1 0-3.616 1.808 1.808 0 0 1 0 3.616zM6.787 20.452H3.886V9h2.901v11.452zM22.225 0H1.771C.792 0 0 .772 0 1.723v20.549C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.272V1.723C24 .772 23.2 0 22.222 0z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-foreground">🔗 Liên Kết Nhanh</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="text-muted-foreground hover:text-foreground">Trang Chủ</a></li>
                        <li><a href="/san-pham" class="text-muted-foreground hover:text-foreground">Sản Phẩm</a></li>
                        <li><a href="/danh-muc" class="text-muted-foreground hover:text-foreground">Danh Mục</a></li>
                        <li><a href="/lien-he" class="text-muted-foreground hover:text-foreground">Liên Hệ</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-foreground">🤝 Hỗ Trợ Khách Hàng</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/huong-dan-mua-hang" class="text-muted-foreground hover:text-foreground">📖 Hướng Dẫn Mua Hàng</a></li>
                        <li><a href="/bao-hanh" class="text-muted-foreground hover:text-foreground">📌 Chính Sách Bảo Hành</a></li>
                        <li><a href="/doi-tra" class="text-muted-foreground hover:text-foreground">🔄 Đổi Trả Sản Phẩm</a></li>
                        <li><a href="/hinh-thuc-thanh-toan" class="text-muted-foreground hover:text-foreground">💳 Hình Thức Thanh Toán</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-foreground">Thông Tin Liên Hệ</h4>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <p>📍 123 Nguyễn Huệ, Q1, TP.HCM</p>
                        <p>📞 0901 234 567</p>
                        <p>✉️ info@storephone.vn</p>
                        <p>🕒 8:00 - 22:00 (Hàng ngày)</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-border mt-8 pt-6">
                <p class="text-center text-sm text-muted-foreground">
                    &copy; <?php echo date('Y'); ?> Thiết kế bởi StorePhone Team | Chính sách bảo mật.
                </p>

                <!-- SQL Debug Info (only in development) -->
                <?php if (isset($GLOBALS['sql_logger']) && !($_ENV['DISABLE_SQL_LOGGING'] ?? false)): ?>
                    <?php $logger = $GLOBALS['sql_logger']; ?>
                    <div class="text-center text-xs text-muted-foreground mt-2 border-t pt-2">
                        📊 SQL Debug: <?= $logger->getQueryCount() ?> queries executed in <?= round($logger->getTotalExecutionTime() * 1000, 2) ?> ms
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</body>

</html>