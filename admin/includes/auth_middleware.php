<?php
require_once '../includes/auth.php';

function authMiddleware() {
    if (!isLoggedIn()) {
        header("Location: ../views/login.php");
        exit();
    }
}

function guestMiddleware() {
    if (isLoggedIn()) {
        header("Location: ../views/dashboard.php");
        exit();
    }
}
?>