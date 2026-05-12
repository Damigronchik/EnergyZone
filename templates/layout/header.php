<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Zone</title>
    <?php 
    $subpage = substr($page, 0, 8) == 'trainer/' ? substr($page, 8) : $page;
    $pageCss = STYLE_PATH . "{$subpage}.css"; ?>
    <link rel="stylesheet" href="<?= $pageCss ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="header">
        <nav class="header__nav">
            <div class="header__logo"><img class="header__logo-picture" src="<?= IMG_PATH ?>logo.png" alt="logo"></div>
            <div class="header__center-links">
                <?php
                $_SESSION['in_account'] = $_SESSION['in_account'] ?? False;

                $menuItems = [
                    'Главная' =>  "index.php",
                    'Информаця' => [
                        'О нас' => "index.php?page=about",
                        'Контакты' => "index.php?page=contact",
                    ],
                    'Абонементы' => "index.php?page=subscriptions",
                    'Занятия' => [
                        'Расписание занятий' => "index.php?page=training-schedule",
                        'Список программ' => "index.php?page=programs-list",
                        'Тренера' => "index.php?page=trainers-list",
                    ],
                ];
                
                function create_menu($items) {
                    echo '<ul class="header__menu">';
                    foreach ($items as $name => $url) {
                        $has_submenu = is_array($url);
                        echo '<li class="header__menu-item' . ($has_submenu ? ' header__menu-item_submenu">' : '">');              
                        if ($has_submenu) {
                            echo '<a href="#" class="header__link">' . $name . '</a>';
                            create_menu($url);
                        } else {
                            echo '<a href="' . $url . '" class="header__link">' . $name . '</a>';
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                create_menu($menuItems);
                ?>
            </div>
            <?php if (isset($_SESSION['in_account']) && $_SESSION['in_account']): ?>
                <a href="index.php?page=account" class="header__link header__menu-item">Личный кабинет</a>
            <?php elseif (isset($_SESSION['trainer_logged_in']) && $_SESSION['trainer_logged_in']): ?>
                <a href="index.php?page=trainer/account" class="header__link header__menu-item">Личный кабинет</a>
            <?php else: ?>
                <a href="#" class="header__link header__menu-item" id="accountButton">Войти</a>
            <?php endif; ?>
        </nav>
        
        <?php $_SESSION['now_url'] = $_SERVER['REQUEST_URI'] ?>

        <div class="registration" id="regModalWindow">
            <form action="index.php?action=auth.registration_check" method="POST" class="registration__content" id="regContent">
                <h2 class="registration__title">Регистрация</h2>
                <input type="hidden" name="modal_type" value="regModalWindow">

                <label for="name" class="registration__label">Имя
                    <input type="text" id="name" name="name" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['name_error']) ? 'registration__input_error' : '' ?>"
                        placeholder="Введите имя" value="<?= $_SESSION['form_data']['name'] ?? '' ?>" required>
                    <span class="registration__error"><?= $_SESSION['errors']['name_error'] ?? '' ?></span>
                </label>

                <label for="mail" class="registration__label">Адрес электронной почты
                    <input type="email" id="mail" name="mail" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['mail_error']) ? 'registration__input_error' : '' ?>"
                        placeholder="Введите адрес электронной почты" value="<?= $_SESSION['form_data']['mail'] ?? '' ?>" required>
                    <span class="registration__error"><?= $_SESSION['errors']['mail_error'] ?? '' ?></span>
                </label>

                <label for="phone" class="registration__label">Номер телефона
                    <input type="tel" id="phone" name="phone" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['phone_error']) ? 'registration__input_error' : '' ?>"
                        placeholder="Введите номер телефона" value="<?= $_SESSION['form_data']['phone'] ?? '' ?>" required>
                    <span class="registration__error"><?= $_SESSION['errors']['phone_error'] ?? '' ?></span>
                </label>

                <label for="regPassword" class="registration__label">Пароль
                    <input type="password" id="regPassword" name="password" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['password_error']) ? 'registration__input_error' : '' ?>"
                        placeholder="Введите пароль" value="<?= $_SESSION['form_data']['password'] ?? '' ?>" required>
                    <img src="<?= IMG_PATH ?>hidePassword_icon.png" alt="show" class="registration__show-password" id="regShowPassword">
                    <span class="registration__error"><?= $_SESSION['errors']['password_error'] ?? '' ?></span>
                </label>

                <label for="regRepeatPassword" class="registration__label">Подтвердите пароль
                    <input type="password" id="regRepeatPassword" name="repeat_password" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['repeat-password_error']) ? 'registration__input_error' : '' ?>"
                        placeholder="Повторите введенный пароль" value="<?= $_SESSION['form_data']['repeat_password'] ?? '' ?>" required>
                    <img src="<?= IMG_PATH ?>hidePassword_icon.png" alt="show" class="registration__show-password" id="regShowRepeatPassword">
                    <span class="registration__error"><?= $_SESSION['errors']['repeat-password_error'] ?? '' ?></span>
                </label>

                <p class="registration__prompt">Уже есть аккаунт? <span class="login__prompt_orange" id="regChange">Войти</span></p>
                <button class="registration__done" id="regButton">Зарегистрироваться</button>
                <div class="registration__close" id="regClose"><img class="registration__close-icon" src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></div>
            </form>
        </div>


        <div class="login" id="logModalWindow">
            <form action="index.php?action=auth.login_check" method="POST" class="login__content" id="logContent">
                <h2 class="login__title">Авторизация</h2>
                <input type="hidden" name="modal_type" value="logModalWindow">

                <label for="mail" class="login__label">Адрес электронной почты
                    <input type="email" id="mail" name="mail" class="login__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['mail_error']) ? 'login__input_error' : '' ?>"
                        placeholder="Введите адрес электронной почты" value="<?= $_SESSION['form_data']['mail'] ?? '' ?>" required>
                    <span class="login__error"><?= $_SESSION['errors']['mail_error'] ?? '' ?></span>
                </label>

                <label for="logPassword" class="login__label">Пароль
                    <input type="password" id="logPassword" name="password" class="login__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['password_error']) ? 'login__input_error' : '' ?>"
                        placeholder="Введите пароль" value="<?= $_SESSION['form_data']['password'] ?? '' ?>" required>
                    <img src="<?= IMG_PATH ?>hidePassword_icon.png" alt="show" class="login__show-password" id="logShowPassword">
                    <span class="login__error"><?= $_SESSION['errors']['password_error'] ?? $_SESSION['errors']['login_error'] ?? '' ?></span>
                </label>

                <p class="login__prompt">Еще нет аккаунта? <span class="login__prompt_orange" id="logChange">Создать</span></p>
                <button type="submit" class="login__done" id="logButton" name="operation" value="log">Войти</button>
                <button class="login__close" id="logClose" name="operation" value="close"><img class="login__close-icon" src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </form>
        </div>
    </header>

    <?php
        echo $_SESSION['in_account'] ? '' : '<script src="' . JS_PATH . 'regLogModalWindow.js"></script>';

        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])){
            echo "<script>document.getElementById('" . $_SESSION['form_data']['modal_type'] . "').style.display = 'flex'</script>";
        }
    ?>