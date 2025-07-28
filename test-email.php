<?php

require_once 'vendor/autoload.php';

use App\Controllers\EmailController;

// Test email functionality
try {
    $emailController = new EmailController();
    
    echo "<h1>Test Email Functionality</h1>";
    
    // Test welcome email
    echo "<h2>Testing Welcome Email...</h2>";
    $emailController->testEmail();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure to update email configuration in config/email.php</p>";
}