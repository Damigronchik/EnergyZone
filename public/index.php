<?php 
session_start();
ob_start();

require_once __DIR__ . '/../config/config.php';
// require_once __DIR__ . '/../vendor/autoload.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;

$page = $_GET['page'] ?? 'main';
// $page = $_GET['page'];
$action = $_GET['action'] ?? null;
$scripts = [];

if (isset($_SESSION['trainer_logged_in'])) {
    $header = 'trainer_header';
} else {
    $header = substr($page, 0, 5) == 'admin' ? 'admin_header' : 'header';
}
$footer = substr($page, 0, 5) == 'admin' ? 'admin_footer' : 'footer';

include __DIR__ . "/../src/core/link.php";
include __DIR__ . "/../templates/layout/{$header}.php";

$page = str_replace('.', '/', $page);
$templatePath = __DIR__ . "/../templates/{$page}.php";
if (file_exists($templatePath)) {
    include $templatePath;
} else {
    include __DIR__ . '/../templates/404.php';
}

foreach ($scripts as $script) {
    echo '<script src="' . $script . '"></script>';
}

include __DIR__ . "/../templates/layout/{$footer}.php";

if ($action) {
    $action = str_replace('.', '/', $action);
    $actionPath = __DIR__ . "/../src/{$action}.php";
    if (file_exists($actionPath)) {
        require_once $actionPath;
        exit;
    } else {
        die();
    }
}

?>
