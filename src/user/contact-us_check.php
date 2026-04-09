<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $mail = trim($_POST['mail']);
        $message = trim($_POST['message']);
        $form_data = [
            'name' => $name,
            'mail' => $mail,
            'message' => $message,
        ];

        $errors_flag = false;

        // валидация
        if (empty($name)) {
            $name_error = "Заполните это поле";
            $errors_flag = true;
        } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 50) {
            $name_error = "Имя должно содержать от 2 до 50 символов";
            $errors_flag = true;
        } elseif (!preg_match('/^[a-zA-Zа-яА-Я]+$/u', $name)) {
            $name_error = "Имя должно содержать только русские или английские буквы";
            $errors_flag = true;
        }

        if (empty($mail)) {
            $mail_error = "Заполните это поле";
            $errors_flag = true;
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-z]{3,6}\.[a-z]{2,5}$/', $mail)) {
            $mail_error = "Неккоректный адрес электронной почты";
            $errors_flag = true;
        } elseif (mb_strlen($mail) > 30) {
            $mail_error = "Это поле не может содержать больше 30 символов";
            $errors_flag = true;
        }

        if (empty($message)) {
            $message_error = "Заполните это поле";
            $errors_flag = true;
        } elseif (mb_strlen($message, 'UTF-8') > 300 || mb_strlen($message, 'UTF-8') < 10) {
            $message_error = "Сообщение должно содержать от 10 до 300 символов";
            $errors_flag = true;
        }
        // конец валидации

        if (!$errors_flag) {            
            $message_query = "INSERT INTO user_messages (name, user_mail, message) VALUES ('$name', '$mail', '$message')";
            $message_result = mysqli_query($link, $message_query);
            $form_data = null;
        }
    }
?>