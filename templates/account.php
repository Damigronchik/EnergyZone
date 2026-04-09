<?php 
if (!isset($_SESSION['in_account']) || !isset($_SESSION['user_mail'])) {
    session_abort();
    header('Location: index.php?page=main');
}

$scripts[] = JS_PATH . 'signup_for_training.js'; 
$scripts[] = JS_PATH . 'editData.js'; 

require SRC_PATH . 'user/user-trainings_check.php'; 
require SRC_PATH . 'user/user-subs_check.php'; 

$user_mail = $_SESSION['user_mail'];
$user_name = $_SESSION['user_name'];
$user_phone_result = mysqli_query($link, "SELECT phone FROM users WHERE mail = '$user_mail'");
$user_phone = mysqli_fetch_row($user_phone_result)[0];

function getPrograms($result, $field_name) {
    $programs = [];
    $row = mysqli_fetch_assoc($result);
    while ($row) {
        array_push($programs, $row[$field_name]);
        $row = mysqli_fetch_assoc($result);
    }
    return $programs;
}

?>

<main>
    <section class="welcome">
        <h1 class="welcome__greetings" data-text="Добро пожаловать, <?= $user_name ?>!">Добро пожаловать, <?= $user_name ?>!</h1>
        <p class="welcome__description">Это ваш личный кабинет. Здесь вы можете просмотреть свои записи на тренировку и приобретенные абонементы.</p>
    </section>

    <section class="trainings">
        <h1 class="trainings__title">Ближайшие тренировки</h1>
        <div class="trainings__all">
            <?php
                $user_train_query = "SELECT * FROM user_trainings WHERE user_mail = '$user_mail'";
                $user_train_result = mysqli_query($link, $user_train_query);

                if ($user_train_result) {
                    if (mysqli_num_rows($user_train_result) == 0) {
                        ?>
    
                        <div class="trainings__empty">
                            <p>Вы пока что не записаны ни на одну тренировку</p>
                            <button class="trainings__button"><a href="index.php?page=training-schedule" class="subscriptions__link">Перейти к расписанию</a></button>
                        </div>
                        
                        <?php
                    } else {
                        $user_train_rows = mysqli_num_rows($user_train_result);
                        for ($i = 0; $i < $user_train_rows; $i++) {
                            echo '<div class="trainings__info">';
                            $user_train_row = mysqli_fetch_row($user_train_result);
                            $trainings_result = mysqli_query($link, "SELECT * FROM training_schedule WHERE id = $user_train_row[2]");
            
                            if ($trainings_result) {
                                $row = mysqli_fetch_row($trainings_result);
                                $trainer_id = $row[4];
                                $trainer_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                                $trainer_name = mysqli_fetch_row($trainer_result)[0];

                                $weekday_in_russian = ['Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday'  => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресенье'];
                                $train_datetime = new DateTime($user_train_row[3]);
                                ?>

                                <p class="trainings__label"><span class="trainings__name">Занятие:</span> <?= $row[3] ?></p>
                                <p class="trainings__label"><span class="trainings__name">Тренер:</span> <?= $trainer_name ?></p>
                                <p class="trainings__label"><span class="trainings__name">Время:</span> <?= $row[2] ?>:00</p>
                                <p class="trainings__label"><span class="trainings__name">Дата:</span> <?= $train_datetime->format('d.m.Y') ?> (<?= $weekday_in_russian[$row[1]] ?>)</p>
                                <form method="post">
                                    <input type="hidden" name="user_train_id" value="<?= $user_train_row[0] ?>">
                                    <button type="submit">Отменить запись</button>
                                </form>

                                <?php
                            echo '</div>';
                            }   
                        }
                    }
                }

            ?>
        </div>
    </section>

    <section class="subscriptions">
        <h2 class="subscriptions__title">Действующие абонементы</h2>
        <div class="subscriptions__cards">
            <?php
            $user_subs_result = mysqli_query($link, "SELECT subscription_id, end_date FROM user_subscriptions WHERE user_mail = '$user_mail'");

            if ($user_subs_result):
                $rows = mysqli_num_rows($user_subs_result);
                if ($rows == 0):
                    ?>
                    <div class="subscriptions__empty">
                        <p>Вы пока что не приобрели ни одного абонемента</p>
                        <button class="subscriptions__button"><a href="index.php?page=subscriptions" class="subscriptions__link">Приобрести абонементы</a></button>
                    </div>
                <?php else:
                    for ($i = 0; $i < $rows; $i++):
                        $user_sub_row = mysqli_fetch_row($user_subs_result);
                        $end_date = new DateTime($user_sub_row[1]);
                        $end_date = $end_date->format('d.m.Y');

                        $sub_id = $user_sub_row[0];
                        $subs_result = mysqli_query($link, "SELECT name FROM subscriptions WHERE id = $sub_id");;
                        $sub_name = mysqli_fetch_row($subs_result)[0];

                        $sub_programs_result = mysqli_query($link, "SELECT training_name FROM subscription_programs WHERE subscription_id = $sub_id");
                        $sub_programs = getPrograms($sub_programs_result, 'training_name');
                        ?>
                        <div class="card" action="index.php?action=user/get_sub" method="post">
                            <div class="card__info">
                                <h6><?= $sub_name ?></h6>
                                <div class="card__line"></div>
                            </div>
                            <div class="card__advantages">
                                <?php
                                foreach ($sub_programs as $program): ?>
                                    <div class="card__advantage">
                                        <img src="<?= IMG_PATH ?>abonement_advantage_icon.png" alt="icon">
                                        <p><?= $program ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <p>Действителен до <?= $end_date ?></p>
                        </div>
                    <?php endfor;
                endif;
            endif; ?>
        </div>
    </section>

    <section class="account">
        <h2 class="account__title">Профиль</h2>

        <form action="index.php?action=user/edit_data" method="POST" class="registration__content" id="regContent">
            <input type="hidden" name="operation" value="edit_data">
            
            <label for="name" class="registration__label">Имя
                <input type="text" id="name" name="name" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['name_error']) ? 'registration__input_error' : '' ?>"
                    placeholder="Введите имя" value="<?= $user_name ?? '' ?>" required>
                <span class="registration__error"><?= $_SESSION['errors']['name_error'] ?? '' ?></span>
            </label>

            <label for="phone" class="registration__label">Номер телефона
                <input type="tel" id="phone" name="phone" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['phone_error']) ? 'registration__input_error' : '' ?>"
                    placeholder="Введите номер телефона" value="<?= $user_phone ?? '' ?>" required>
                <span class="registration__error"><?= $_SESSION['errors']['phone_error'] ?? '' ?></span>
            </label>

            <button class="registration__done" id="regButton">Сохранить изменения</button>
        </form>
        
        <button type="button" id="editPasswordButton" class="account__logout">Изменить пароль</button>
        <form action="index.php?action=user/logout" method="post">
            <button class="account__logout">Выйти из аккаунта</button>
        </form>
    </section>

    <section class="trainings">
        <h1 class="trainings__title">История тренировок</h1>
        <div class="trainings__all">
            <?php
                $user_train_query = "SELECT * FROM old_user_trainings WHERE user_mail = '$user_mail' ORDER BY training_datetime DESC";
                $user_train_result = mysqli_query($link, $user_train_query);

                if ($user_train_result) {
                    if (mysqli_num_rows($user_train_result) == 0) {
                        ?>
    
                        <div class="trainings__empty">
                            <p>В истории нет записей о прошлых тренировках</p>
                        </div>
                        
                        <?php
                    } else {
                        $user_train_rows = mysqli_num_rows($user_train_result);
                        for ($i = 0; $i < $user_train_rows; $i++) {
                            echo '<div class="trainings__info">';
                            $user_train_row = mysqli_fetch_row($user_train_result);
                            $trainings_result = mysqli_query($link, "SELECT * FROM training_schedule WHERE id = $user_train_row[2]");
            
                            if ($trainings_result) {
                                $row = mysqli_fetch_row($trainings_result);
                                $trainer_id = $row[4];
                                $trainer_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                                $trainer_name = mysqli_fetch_row($trainer_result)[0];
                                $train_datetime = new DateTime($user_train_row[3]);
                                ?>
                                <p class="trainings__label"><span class="trainings__name">Занятие:</span> <?= $row[3] ?></p>
                                <p class="trainings__label"><span class="trainings__name">Тренер:</span> <?= $trainer_name ?></p>
                                <p class="trainings__label"><span class="trainings__name">Время:</span> <?= $row[2] ?>:00</p>
                                <p class="trainings__label"><span class="trainings__name">Дата:</span> <?= $train_datetime->format('d.m.Y') ?></p>
                                <?php if ($user_train_row[4] == false): ?>
                                    <form action="index.php?action=user/train_rating" method="post">
                                        <input type="hidden" name="train_schedule_id" value="<?= $user_train_row[2] ?>">
                                        <input type="hidden" name="user_train_id" value="<?= $user_train_row[0] ?>">
                                        <label>
                                            <input type="radio" name="rating" value="1">1
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" value="2">2
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" value="3">3
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" value="4">4
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" value="5" checked>5
                                        </label>
                                        <button type="submit">Оценить</button>
                                    </form>
                                <?php endif;
                            echo '</div>';
                            }   
                        }
                    }
                }
            ?>
        </div>
    </section>

    <section>
        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Отменить запись?</h2>
                <div class="modal-window__buttons">
                    <form action="index.php?action=user/revoke_user_train" method="post">
                        <input type="hidden" name="user_train_id" value="<?= $_POST['user_train_id'] ?>">
                        <button type="submit">Отменить</button>
                    </form>
                    <button class="modal-window__goback" id="goBackButton">Закрыть окно</button>
                </div>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>

        <div class="modal-window" id="successWindow">
            <div class="modal-window__content" id="successContent">
                <h2 class="modal-window__title">Запись отменена!</h2>
                <p class="modal-window__description">Средства вернут в течении 5 дней</p>
                <div class="modal-window__buttons">
                    <button class="modal-window__account" id="goSchedule">Закрыть окно</button>
                </div>
                <button class="modal-window__close-icon" id="goSchedule"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>

        <div class="modal-window" id="editPassword">
            <div class="modal-window__content">
                <h2 class="modal-window__title">Смена пароля</h2>
                <div class="modal-window__buttons">
                    <form action="index.php?action=user/edit_data" method="post">
                        <input type="hidden" name="operation" value="edit_password">

                        <label for="regPassword" class="registration__label">Старый пароль
                            <input type="password" id="regPassword" name="old_password" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['password_error']) ? 'registration__input_error' : '' ?>"
                                placeholder="Введите старый пароль" value="<?= $_SESSION['form_data']['password'] ?? '' ?>" required>
                            <img src="<?= IMG_PATH ?>hidePassword_icon.png" alt="show" class="registration__show-password" id="regShowPassword">
                            <span class="registration__error"><?= $_SESSION['old_password_error'] ?? '' ?></span>
                        </label>

                        <label for="regRepeatPassword" class="registration__label">Новый пароль
                            <input type="password" id="regRepeatPassword" name="new_password" class="registration__input <?= isset($_SESSION['errors']) && !empty($_SESSION['errors']['repeat-password_error']) ? 'registration__input_error' : '' ?>"
                                placeholder="Введите новый пароль" value="<?= $_SESSION['form_data']['repeat_password'] ?? '' ?>" required>
                            <img src="<?= IMG_PATH ?>hidePassword_icon.png" alt="show" class="registration__show-password" id="regShowRepeatPassword">
                            <span class="registration__error"><?= $_SESSION['errors']['repeat-password_error'] ?? '' ?></span>
                        </label>
                        
                        <button type="submit">Изменить</button>
                    </form>
                </div>
                <button class="modal-window__close-icon" id="editPasswordClose"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>

<?php
if (isset($_POST['user_train_id'])) {
    echo "<script>document.getElementById('modalWindow').style.display = 'flex'</script>";
}

if (isset($_SESSION['is_train_delete'])) {
    echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
    echo "<script>document.getElementById('successWindow').style.display = 'flex'</script>";
    unset($_SESSION['is_train_delete']);
}

if (isset($_SESSION['edit_password_window'])) {
    echo "<script>document.getElementById('editPassword').style.display = 'flex'</script>";
    unset($_SESSION['edit_password_window']);
    unset($_SESSION['old_password_error']);
}
?>