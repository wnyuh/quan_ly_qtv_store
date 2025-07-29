<!DOCTYPE html>
<html lang="en">
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
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/basecoat-css@0.2.8/dist/js/all.min.js" defer></script>
</head>

<body class="bg-background text-foreground">
    <!-- Sidebar -->
    <aside class="sidebar" data-side="left" aria-hidden="false">
        <nav aria-label="Sidebar navigation">
            <section class="scrollbar">
                <div role="group" aria-labelledby="group-label-admin">
                    <h3 id="group-label-admin">Quản trị</h3>
                    
                    <ul>
                        <li>
                            <a href="/admin/dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/san-pham">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 8V4H8"/>
                                    <rect width="16" height="12" x="4" y="8" rx="2"/>
                                    <path d="M2 14h2"/>
                                    <path d="M20 14h2"/>
                                    <path d="M15 13v2"/>
                                    <path d="M9 13v2"/>
                                </svg>
                                <span>Sản phẩm</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/danh-muc">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 5h18M3 12h18M3 19h18"/>
                                </svg>
                                <span>Danh mục</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/thuong-hieu" class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <!-- Tag icon -->
                                    <path d="M4 3a1 1 0 00-1 1v6a1 1 0 00.293.707l8 8a1 1 0 001.414 0l6-6a1 1 0 00.293-.707V4a1 1 0 00-1-1H4z"/>
                                    <circle cx="9" cy="8" r="2"/>
                                </svg>
                                <span>Thương hiệu</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/nguoi-dung">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M4 21v-2a4 4 0 0 1 3-3.87" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>Người dùng</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/don-hang">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                                </svg>
                                <span>Đơn hàng</span>
                            </a>
                        </li>

                        <li>
                            <a href="/admin/bao-cao/doanh-thu">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3v18h18"/>
                                    <path d="M6 17v-4"/>
                                    <path d="M11 17v-8"/>
                                    <path d="M16 17v-12"/>
                                    <path d="M21 17v-6"/>
                                </svg>
                                <span>Báo cáo doanh thu</span>
                            </a>
                        </li>

                        <li>
                            <details id="submenu-settings">
                                <summary aria-controls="submenu-settings-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Cài đặt
                                </summary>
                                <ul id="submenu-settings-content">
                                    <li>
                                        <a href="#">
                                            <span>Chung</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span>Khách hàng</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span>Thanh toán</span>
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
            </section>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- Top Header -->
        <header class="border-b bg-card px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Left side: Sidebar toggle -->
                <div class="flex items-center space-x-4">
                    <button type="button" onclick="document.dispatchEvent(new CustomEvent('basecoat:sidebar'))" class="btn-icon-outline size-8" aria-label="Toggle sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6"/>
                            <line x1="3" x2="21" y1="12" y2="12"/>
                            <line x1="3" x2="21" y1="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <!-- Center: Page title and user greeting -->
                <div class="flex-1 text-center">
                    <h2 class="text-lg font-semibold text-foreground"><?php echo $pageTitle ?? 'Dashboard'; ?></h2>
                    <?php if (isset($_SESSION['admin_username'])): ?>
                        <p class="text-sm text-muted-foreground">
                            Xin chào, <span class="font-medium text-foreground"><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Right side: Theme toggle and Sign out -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button
                        type="button"
                        aria-label="Toggle dark mode"
                        data-tooltip="Toggle dark mode"
                        data-side="bottom"
                        onclick="document.dispatchEvent(new CustomEvent('basecoat:theme'))"
                        class="btn-icon-outline size-8"
                    >
                        <span class="hidden dark:block"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4" /><path d="M12 2v2" /><path d="M12 20v2" /><path d="m4.93 4.93 1.41 1.41" /><path d="m17.66 17.66 1.41 1.41" /><path d="M2 12h2" /><path d="M20 12h2" /><path d="m6.34 17.66-1.41 1.41" /><path d="m19.07 4.93-1.41 1.41" /></svg></span>
                        <span class="block dark:hidden"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" /></svg></span>
                    </button>

                    <!-- Sign out -->
                    <a href="/admin/logout" class="btn-outline text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16,17 21,12 16,7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        Đăng xuất
                    </a>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-6">
            <?php echo $content; ?>
        </div>
    </main>
</body>
</html>
