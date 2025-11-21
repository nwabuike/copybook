<?php
// logout.php - Logout handler
require_once 'php/auth.php';

logoutUser();
header('Location: login.php?message=logged_out');
exit();
?>
