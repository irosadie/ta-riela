<?php
$baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . '' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$homeUrl = $baseUrl . "administrator/";
return [
    'senderEmail' => 'Admin Russindo',
    'senderName' => 'Admin Russindo',
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'passwordResetTokenExpire' => 3600,
    'baseUrl' => 'http://localhost:5000/',
];