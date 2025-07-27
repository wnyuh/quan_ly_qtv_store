document.addEventListener('DOMContentLoaded', function() {
    // Xử lý form thêm sản phẩm
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    alert('Vui lòng điền đầy đủ thông tin!');
                    e.preventDefault();
                }
            });
            if (isValid) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang xử lý...';
            }
        });
    });

    // Xử lý nút xóa trong giỏ hàng
    const removeLinks = document.querySelectorAll('a[href*="?remove="]');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                e.preventDefault();
            }
        });
    });
});