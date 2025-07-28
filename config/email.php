<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    // Mailtrap Configuration (for development/testing)
    'host' => 'sandbox.smtp.mailtrap.io',
    'port' => 2525,
    'encryption' => '', // Mailtrap doesn't require encryption
    
    // Mailtrap Authentication
    'username' => $_ENV['MAILTRAP_USERNAME'],
    'password' => $_ENV['MAILTRAP_PASSWORD'],
    
    // Sender Info
    'from_email' => 'noreply@cuahangstore.com',
    'from_name' => 'Cửa hàng Store',
    
    // Other settings
    'charset' => 'UTF-8',
    'timeout' => 30,
    
    // Production email providers (uncomment when ready for production):
    
    // Gmail
    // 'host' => 'smtp.gmail.com',
    // 'port' => 587,
    // 'encryption' => 'tls',
    // 'username' => 'your-email@gmail.com',
    // 'password' => 'your-app-password',
    
    // Outlook/Hotmail
    // 'host' => 'smtp-mail.outlook.com',
    // 'port' => 587,
    // 'encryption' => 'tls',
    
    // Yahoo
    // 'host' => 'smtp.mail.yahoo.com',
    // 'port' => 587,
    // 'encryption' => 'tls',
    
    // Custom SMTP (VPS/Hosting)
    // 'host' => 'mail.yourdomain.com',
    // 'port' => 587,
    // 'encryption' => 'tls',
];
