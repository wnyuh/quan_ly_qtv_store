<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <script>
      (() => {
        try {
          const stored = localStorage.getItem('themeMode');
          if (stored ? stored === 'dark'
            : matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
          }
        } catch (_) {}

        const apply = dark => {
          document.documentElement.classList.toggle('dark', dark);
          try { localStorage.setItem('themeMode', dark ? 'dark' : 'light'); } catch (_) {}
        };

        document.addEventListener('basecoat:theme', (event) => {
          const mode = event.detail?.mode;
          apply(mode === 'dark' ? true
            : mode === 'light' ? false
              : !document.documentElement.classList.contains('dark'));
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
            <div class="flex items-center space-x-3">
                <div class="text-2xl">📱</div>
                <div>
                    <h1 class="text-xl font-bold text-foreground">Store Phone</h1>
                    <p class="text-xs text-muted-foreground">Cửa hàng điện thoại uy tín</p>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Trang Chủ</a>
                <a href="/tim-kiem-san-pham" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Sản Phẩm</a>
                <a href="/danh-muc" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Danh Mục</a>
                <a href="/lien-he" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Liên Hệ</a>
            </nav>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search Button -->
                <a href="/tim-kiem-san-pham" class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </a>

                <!-- Cart Button -->
                <button class="relative p-2 text-muted-foreground hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L23 6H6"/></svg>

                    <span id="cart-item-count" class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-xs rounded-full w-5 h-5 flex items-center justify-center"><?= $cartItemCount ?? 0 ?></span>
                </button>

                <!-- User Menu -->
                <div class="flex items-center space-x-2">
                    <button class="p-2 text-muted-foreground hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </button>
                </div>

                <!-- Theme Toggle -->
                <button
                        type="button"
                        aria-label="Toggle dark mode"
                        data-tooltip="Toggle dark mode"
                        data-side="bottom"
                        onclick="document.dispatchEvent(new CustomEvent('basecoat:theme'))"
                        class="p-2 text-muted-foreground hover:text-foreground transition-colors"
                >
                    <span class="hidden dark:block"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4" /><path d="M12 2v2" /><path d="M12 20v2" /><path d="m4.93 4.93 1.41 1.41" /><path d="m17.66 17.66 1.41 1.41" /><path d="M2 12h2" /><path d="M20 12h2" /><path d="m6.34 17.66-1.41 1.41" /><path d="m19.07 4.93-1.41 1.41" /></svg></span>
                    <span class="block dark:hidden"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" /></svg></span>
                </button>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-muted-foreground hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</header>

<main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php echo $content; ?>
</main>

<footer class="border-t bg-card mt-auto">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="text-xl">📱</div>
                    <h3 class="font-bold text-foreground">Store Phone</h3>
                </div>
                <p class="text-sm text-muted-foreground">Cửa hàng điện thoại uy tín, chất lượng cao với giá cả phải chăng.</p>
                <div class="flex space-x-3">
                    <a href="#" class="text-muted-foreground hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="text-muted-foreground hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h4 class="font-semibold text-foreground">Liên Kết Nhanh</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="text-muted-foreground hover:text-foreground">Trang Chủ</a></li>
                    <li><a href="/san-pham" class="text-muted-foreground hover:text-foreground">Sản Phẩm</a></li>
                    <li><a href="/danh-muc" class="text-muted-foreground hover:text-foreground">Danh Mục</a></li>
                    <li><a href="/lien-he" class="text-muted-foreground hover:text-foreground">Liên Hệ</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="space-y-4">
                <h4 class="font-semibold text-foreground">Hỗ Trợ Khách Hàng</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/huong-dan" class="text-muted-foreground hover:text-foreground">Hướng Dẫn Mua Hàng</a></li>
                    <li><a href="/bao-hanh" class="text-muted-foreground hover:text-foreground">Chính Sách Bảo Hành</a></li>
                    <li><a href="/doi-tra" class="text-muted-foreground hover:text-foreground">Đổi Trả Sản Phẩm</a></li>
                    <li><a href="/thanh-toan" class="text-muted-foreground hover:text-foreground">Hình Thức Thanh Toán</a></li>
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
                &copy; <?php echo date('Y'); ?> Store Phone. Tất cả quyền được bảo lưu.
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
