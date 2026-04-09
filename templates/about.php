<?php 
$scripts[] = JS_PATH . 'send_message.js';
require SRC_PATH . 'user/contact-us_check.php';
?>

<main>
    <section class="about">
        <img class="about__image" src="<?= IMG_PATH ?>about.png" alt="gym_photo">
        <div class="about__info">
            <h2 class="about__title">Добро пожаловать в EnergyZone</h2>
            <p class="about__text">Основанный в 2015 году, Energyzone превратился в тренажерный зал и фитнес-центр, ориентированный на желания и потребности общества, призванный помогать людям с любым уровнем физической подготовки достигать своих целей с помощью инновационных программ и современного оборудования.</p>
            <button class="about__button" id="goScheduleButton"><a href="index.php?page=training-schedule">Записаться на тренировку</a></button>
        </div>
    </section>

    <section class="contact-us">
        <form method="post" class="form">
            <h2 class="form__title">Свяжитесь с нами</h2>
            <div class="form__input-fields">
                <div class="form__left-inputs">
                    <label class="form__label" for="name">Имя
                        <input class="form__input <?= $name_error ? 'form__input_error' : '' ?>" type="text" id="name" name="name"
                            value="<?= $form_data['name'] ?? $_SESSION['user_name'] ?? '' ?>" placeholder="Введите имя" required>
                        <span class="form__error"><?= $name_error ?? '' ?></span>
                    </label>
                    <label class="form__label" for="mail">Почта
                        <input class="form__input <?= $mail_error ? 'form__input_error' : '' ?>" type="mail" id="mail" name="mail"
                            value="<?= $form_data['mail'] ?? $_SESSION['user_mail'] ?? '' ?>" placeholder="Введит адрес электронной почты" required>
                        <span class="form__error"><?= $mail_error ?? '' ?></span>
                    </label>
                </div>
                <label class="form__label" for="message">Сообщение
                    <textarea class="form__input <?= $message_error ? 'form__input_error' : '' ?> form__input_big" id="message" name="message" placeholder="Введите сообщение" required><?= $form_data['message'] ?? '' ?></textarea>
                    <span class="form__error"><?= $message_error ?? '' ?></span>
                </label>
            </div>
            <label class="form__agreement" for="agreement">
                <input class="form__checkbox" type="checkbox" id="agreement" required>
                Я согласен на обработку персональных данных
            </label>
            <button class="form__button" id="sendButton">Отправить сообщение</button>
        </form>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Сообщение отправлено</h2>
                <p class="modal-window__text">Ваше сообщение скоро будет прочитано. Спасибо за обратную связь, это помогает нам становится лучше!</p>
                <div class="modal-window__buttons">
                    <button class="modal-window__homepage" id="homeButton"><a href="index.php">Вернуться на главную</a></button>
                    <button class="modal-window__close" id="textCloseButton">Закрыть окно</button>
                </div>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>


<?= isset($errors_flag) && !$errors_flag ? '<script>modalWindow.style.display = "flex"</script>' : '' ?>