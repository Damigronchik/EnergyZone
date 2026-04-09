<?php
session_destroy();
header('Location: index.php');
ob_end_flush();
?>