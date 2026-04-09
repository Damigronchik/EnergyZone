<?php 
$scripts[] = JS_PATH . 'send_message.js';
require SRC_PATH . 'user/contact-us_check.php';
?>

<main>
    <section class="contact-us">
        <h2 class="contact-us__title">Свяжитесь с нами</h2>
        <form method="post" class="form">
            <div class="form__inputs-and-info">
                <div class="form__input-fields">
                    <input type="hidden" name="url" value="<?= $_SERVER['REQUEST_URI'] ?>">
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
                    <label class="form__label" for="message">Сообщение
                        <textarea class="form__input <?= $message_error ? 'form__input_error' : '' ?> form__input_big" id="message" name="message" placeholder="Введите сообщение" required><?= $form_data['message'] ?? '' ?></textarea>
                        <span class="form__error"><?= $message_error ?? '' ?></span>
                    </label>
                </div>

                <div class="info">
                    <div class="info__block">
                        <img src="<?= IMG_PATH ?>address_icon.png" alt="address_icon" class="info__image">
                        <h4 class="info__name">Адрес</h4>
                        <p class="info__text">ул. Максима Богдановича, д.74</p>
                    </div>
                    <div class="info__block">
                        <img src="<?= IMG_PATH ?>time_icon.png" alt="time_icon" class="info__image">
                        <h4 class="info__name">Время работы</h4>
                        <p class="info__text">Ежедневно с 9:00 до 21:00</p>
                    </div>
                </div>

                <div class="info">
                    <div class="info__block">
                        <img src="<?= IMG_PATH ?>phone_icon.png" alt="phone_icon" class="info__image">
                        <h4 class="info__name">Телефон</h4>
                        <p class="info__text">+375 (29) 340-07-10</p>
                    </div>
                    <div class="info__block">
                        <img src="<?= IMG_PATH ?>mail_icon.png" alt="mail_icon" class="info__image">
                        <h4 class="info__name">Почта</h4>
                        <p class="info__text">energyzone.info@gmail.com</p>
                    </div>
                </div>                    
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
