<?php
session_start();
session_destroy();

//setcookie("admin_login", "", time() - 3600, "/");

header("Location: index.html");
exit();
?>
