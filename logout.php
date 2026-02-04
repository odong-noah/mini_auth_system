<?php
session_start();
session_destroy();
header("Location: auth_login_sys.php");
exit();