<div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <?php if (!empty($success)): ?>
            <!-- Success State -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold leading-9 tracking-tight text-foreground mb-4">
                    Xác nhận thành công!
                </h2>
                <div class="mb-8 rounded-md border border-green-500/50 bg-green-500/10 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span><?= htmlspecialchars($success) ?></span>
                    </div>
                </div>
                <div class="space-y-4">
                    <a href="/dang-nhap" 
                        class="inline-flex w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        Đăng nhập ngay
                    </a>
                    <a href="/" 
                        class="inline-flex w-full items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        Về trang chủ
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Error State -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold leading-9 tracking-tight text-foreground mb-4">
                    Xác nhận không thành công
                </h2>
                <div class="mb-8 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" x2="9" y1="9" y2="15"/>
                            <line x1="9" x2="15" y1="9" y2="15"/>
                        </svg>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                </div>
                <div class="space-y-4">
                    <a href="/dang-ky" 
                        class="inline-flex w-full items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        Đăng ký lại
                    </a>
                    <a href="/" 
                        class="inline-flex w-full items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        Về trang chủ
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-10 text-center">
            <p class="text-sm text-muted-foreground">
                Cần hỗ trợ? 
                <a href="/lien-he" class="font-semibold leading-6 text-primary hover:text-primary/80">
                    Liên hệ với chúng tôi
                </a>
            </p>
        </div>
    </div>
</div>