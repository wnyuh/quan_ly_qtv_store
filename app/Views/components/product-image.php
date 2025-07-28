<?php

/**
 * Hiển thị ảnh sản phẩm
 * Nhận các biến: $image (string), $alt (string), $size (sm|md|lg)
 */
$size = $size ?? 'md';
$sizing = [
    'sm' => 'w-12 h-12',
    'md' => 'w-16 h-16',
    'lg' => 'w-28 h-28'
];
$cls = $sizing[$size] ?? $sizing['md'];
?>

<?php if (!empty($image)): ?>
    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt ?? '') ?>"
        class="<?= $cls ?> object-cover rounded-xl bg-slate-200 border border-border shadow" />
<?php else: ?>
    <span class="inline-flex items-center justify-center bg-muted rounded-full <?= $cls ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <rect width="18" height="18" x="3" y="3" rx="2" />
            <path d="m9 9 6 6M15 9l-6 6" />
        </svg>
    </span>
<?php endif; ?>