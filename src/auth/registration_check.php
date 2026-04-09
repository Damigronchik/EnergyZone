<?php
    $previous_url = $_SERVER['HTTP_REFERER'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $mail = trim($_POST['mail']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);
        $repeat_password = trim($_POST['repeat_password']);
        $modal_type = $_POST['modal_type'];
        $_SESSION['form_data'] = [
            'name' => $name,
            'mail' => $mail,
            'phone' => $phone,
            'password' => $password,
            'repeat_password' => $repeat_password,
            'modal_type' => $modal_type,
        ];

        $errors_flag = false;
        $_SESSION['errors'] = [
            'name_error' => null,
            'mail_error' => null,
            'phone_error' => null,
            'password_error' => null,
            'repeat-password_error' => null,
        ];

        //валидация
        if (empty($name)) {
            $_SESSION['errors']['name_error'] = "Заполните это поле";
            $errors_flag = true;
        } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 50) {
            $_SESSION['errors']['name_error'] = "Имя может содержать от 2 до 50 символов";
            $errors_flag = true;
        } elseif (!preg_match('/^[a-zA-Zа-яА-Я]+$/u', $name)) {
            $_SESSION['errors']['name_error'] = "Имя может содержать только русские или английские буквы";
            $errors_flag = true;
        }

        if (empty($mail)) {
            $_SESSION['errors']['mail_error'] = "Заполните это поле";
            $errors_flag = true;
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-z]{3,6}\.[a-z]{2,5}$/', $mail)) {
            $_SESSION['errors']['mail_error'] = "Неккоректный адрес (Пример: example@gmail.com)";
            $errors_flag = true;
        } elseif (mb_strlen($mail) > 30) {
            $_SESSION['errors']['mail_error'] = "Это поле не может содержать больше 30 символов";
            $errors_flag = true;
        }

        if (empty($phone)) {
            $_SESSION['errors']['phone_error'] = "Заполните это поле";
            $errors_flag = true;
        } elseif (!preg_match('/^(\+375|375)(\s|-|)?(29|25|33|44)(\s|-)?\d{3}(\s|-)?\d{2}(\s|-)?\d{2}$/', $phone)) {
            $_SESSION['errors']['phone_error'] = "Некоректный номер телефона (Пример: +375 29 123-45-67)";
            $errors_flag = true;
        }

        if (empty($password)) {
            $_SESSION['errors']['password_error'] = "Заполните это поле";
            $errors_flag = true;
        } elseif (!preg_match('/[а-яА-Яa-zA-Z]+/', $password) || !preg_match('/\d/', $password)) {
            $_SESSION['errors']['password_error'] = "Пароль должен содержать хотя бы одну букву и цифру";
            $errors_flag = true;
        } elseif (mb_strlen($password) < 5 || mb_strlen($password) > 30) {
            $_SESSION['errors']['password_error'] = 'Пароль может содержать от 5 до 30 символов';
            $errors_flag = true;
        } elseif ($password != $repeat_password) {
            $_SESSION['errors']['password_error'] = 'Пароли не совпадают';
            $errors_flag = true;
        }

        if (empty($repeat_password)) {
            $_SESSION['errors']['repeat-password_error'] = "Заполните это поле";
            $errors_flag = true;
        }
        // конец валидации

        if (!$errors_flag) {
            $query_check = "SELECT mail FROM users";
            $result_check = mysqli_query($link, $query_check);
            
            $isset_mail = false;
            if ($result_check) {
                $rows = mysqli_num_rows($result_check);

                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_row($result_check);
                    if ($row[0] == $mail) {
                        $isset_mail = true;
                        break;
                    }
                }
                mysqli_free_result($result_check);
            }        

            if ($isset_mail) {
                $_SESSION['errors']['mail_error'] = "Эта почта уже занята!";
                header("Location: $previus_url");
            } else {
                $hash_password = password_hash($password, PASSWORD_DEFAULT);

                $query_insert = "INSERT INTO users (mail, name, phone, password) 
                    VALUES ('$mail', '$name', '$phone', '$hash_password')";
                $result_insert = mysqli_query($link, $query_insert);

                unset($_SESSION['form_data']);
                unset($_SESSION['errors']);

                $_SESSION['in_account'] = True;
                $_SESSION['user_mail'] = $mail;
                $_SESSION['user_name'] = $name;
                header('Location: index.php?page=account');
            }
        } else {
            header("Location: $previus_url");
        }
    }
?>