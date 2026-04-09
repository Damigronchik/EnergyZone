<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Zone</title>
    <?php 
    $sub_page = substr($page, 6);
    $pageCss = STYLE_PATH . "{$sub_page}.css";
    require_once SRC_PATH . 'auth/admin_check.php';
    ?>
    <link rel="stylesheet" href="<?= $pageCss ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="header">
        <nav class="header__nav">
            <div class="header__logo"><img class="header__logo-picture" src="<?= IMG_PATH ?>logo.png" alt="logo"></div>
            <div class="header__center-links">
                <?php
                session_start();
                $_SESSION['in_account'] = $_SESSION['in_account'] ?? False;

                $menuItems = [
                    'Расписание занятий' => "index.php?page=admin/training-schedule",
                    'Список программ' => "index.php?page=admin/programs-list",
                    'Тренеры' => "index.php?page=admin/trainers-list",
                    'Абонементы' => "index.php?page=admin/subscriptions",
                ];
                
                function create_menu($items) {
                    echo '<ul class="header__menu">';
                    foreach ($items as $name => $url) {
                        echo 
                        '<li class="header__menu-item">
                            <a href="' . $url . '" class="header__link">' . $name . '</a>
                        </li>';
                    }
                    echo '</ul>';
                }
                create_menu($menuItems);
                ?>
            </div>
            <form action="" class="header__menu-item">
                <button class="header__link">Выйти</button>
            </form>
        </nav>
    </header>