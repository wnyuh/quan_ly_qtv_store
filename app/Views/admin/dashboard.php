<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stats Cards -->
        <div class="card p-4">
            <div class="flex items-center">
                <div class="text-2xl mr-3">📱</div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Sản phẩm</p>
                    <p class="text-2xl font-bold text-foreground">
                        <?= $tong_sp_count ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card p-4">
            <div class="flex items-center">
                <div class="text-2xl mr-3">📦</div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Đơn hàng</p>
                    <p class="text-2xl font-bold text-foreground">
                        <?= $don_hang_count ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card p-4">
            <div class="flex items-center">
                <div class="text-2xl mr-3">👥</div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Khách hàng</p>
                    <p class="text-2xl font-bold text-foreground"></p>
                </div>
            </div>
        </div>
        
        <div class="card p-4">
            <div class="flex items-center">
                <div class="text-2xl mr-3">💰</div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Doanh thu</p>
                    <p class="text-2xl font-bold text-foreground">
                        <?= $doanh_thu ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="card p-4">
            <header>
                <h3>Thao tác nhanh</h3>
                <p>Các tính năng thường sử dụng</p>
            </header>
            <section>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-3 hover:bg-muted rounded-lg transition-colors">
                        <div class="text-xl mr-3">➕</div>
                        <div>
                            <p class="font-medium text-foreground">Thêm sản phẩm mới</p>
                            <p class="text-sm text-muted-foreground">Tạo sản phẩm mới trong cửa hàng</p>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center p-3 hover:bg-muted rounded-lg transition-colors">
                        <div class="text-xl mr-3">📊</div>
                        <div>
                            <p class="font-medium text-foreground">Xem báo cáo</p>
                            <p class="text-sm text-muted-foreground">Kiểm tra doanh thu và thống kê</p>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center p-3 hover:bg-muted rounded-lg transition-colors">
                        <div class="text-xl mr-3">⚙️</div>
                        <div>
                            <p class="font-medium text-foreground">Cài đặt</p>
                            <p class="text-sm text-muted-foreground">Quản lý cấu hình hệ thống</p>
                        </div>
                    </a>
                </div>
            </section>
        </div>
        
        <!-- Recent Activity -->
        <div class="card p-4">
            <header>
                <h3>Hoạt động gần đây</h3>
                <p>Các hoạt động mới nhất của hệ thống</p>
            </header>
            <section>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-muted/50 rounded-lg">
                        <div class="text-xl mr-3">📝</div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-foreground">Chưa có hoạt động nào</p>
                            <p class="text-xs text-muted-foreground">Các hoạt động sẽ hiển thị ở đây</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>