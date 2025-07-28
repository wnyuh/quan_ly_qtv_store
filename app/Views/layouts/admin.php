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
    <title><?php echo $pageTitle ?? 'My Awesome Site'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/basecoat-css@0.2.8/dist/js/all.min.js" defer></script>
</head>

<body class="bg-background text-foreground">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-sidebar border-r border-sidebar-border">
        <div class="p-6">
            <h1 class="text-xl font-bold text-sidebar-foreground">Admin Panel</h1>
        </div>
        <nav class="px-3 space-y-1">
            <a href="/admin" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                Dashboard
            </a>
            <a href="/admin/users" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                Users
            </a>
            <a href="/admin/products" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                Products
            </a>
            <a href="/admin/orders" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-colors">
                Orders
            </a>
            <button
                    type="button"
                    aria-label="Toggle dark mode"
                    data-tooltip="Toggle dark mode"
                    data-side="bottom"
                    onclick="document.dispatchEvent(new CustomEvent('basecoat:theme'))"
                    class="btn-icon-outline size-8"
            >
                <span class="hidden dark:block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4" /><path d="M12 2v2" /><path d="M12 20v2" /><path d="m4.93 4.93 1.41 1.41" /><path d="m17.66 17.66 1.41 1.41" /><path d="M2 12h2" /><path d="M20 12h2" /><path d="m6.34 17.66-1.41 1.41" /><path d="m19.07 4.93-1.41 1.41" /></svg></span>
                <span class="block dark:hidden"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" /></svg></span>
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Header -->
        <header class="border-b bg-card px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-foreground"><?php echo $pageTitle ?? 'Dashboard'; ?></h2>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-muted-foreground hover:text-foreground transition-colors">View Site</a>
                    <button class="px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6">
            <?php echo $content; ?>
        </main>
    </div>
</div>
</body>
</html>
