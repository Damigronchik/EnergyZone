<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $operation = $_POST['operation'];
        $previous_url = $_SERVER['HTTP_REFERER'];

        if ($operation == 'close') { 
            clear_session();
            header("Location: $previous_url");
            exit;
        } elseif ($operation == 'log') {
            $mail = trim($_POST['mail']);
            $password = trim($_POST['password']);
            $modal_type = $_POST['modal_type'];
            $_SESSION['form_data'] = [
                'mail' => $mail,
                'password' => $password,
                'previous_url' => $previous_url,
                'modal_type' => $modal_type
            ];
    
            $errors_flag = validation($mail, $password);
            
            if (!$errors_flag) {  
                $query_check = "SELECT mail, password, name FROM users";
                $result_check = mysqli_query($link, $query_check);
                $trainer_query = "SELECT email, password, trainer_id FROM trainer_users";
                $trainer_result = mysqli_query($link, $trainer_query);
                $admin_query = "SELECT email, password FROM admin_users";
                $admin_result = mysqli_query($link, $admin_query);
                
                $isset_user = false;
                $is_trainer = false;
                $is_admin = false;

                if ($result_check) {
                    $rows = mysqli_num_rows($result_check);
                    for ($i = 0; $i < $rows; $i++) {
                        $row = mysqli_fetch_row($result_check);
                        if ($row[0] == $mail && password_verify($password, $row[1])) {
                            $isset_user = true;
                            break;
                        }
                    }
                    mysqli_free_result($result_check);
                } 
                if (!$isset_user) {
                    if ($trainer_result) {
                        $rows = mysqli_num_rows($trainer_result);
                        for ($i = 0; $i < $rows; $i++) {
                            $row = mysqli_fetch_row($trainer_result);
                            if ($row[0] == $mail && password_verify($password, $row[1])) {
                                $is_trainer = true;
                                $trainer_id = $row[2];
                                break;
                            }
                        }
                        mysqli_free_result($trainer_result);
                    } 
                    if ($admin_result && !$is_trainer) {
                        $rows = mysqli_num_rows($admin_result);
                        for ($i = 0; $i < $rows; $i++) {
                            $row = mysqli_fetch_row($admin_result);
                            if ($row[0] == $mail && password_verify($password, $row[1])) {
                                $is_admin = true;
                                break;
                            }
                        }
                        mysqli_free_result($admin_result);
                    }     
                }
                
                if ($isset_user) {
                    clear_session();
                    
                    $_SESSION['in_account'] = true;
                    $_SESSION['user_mail'] = $mail;
                    $_SESSION['user_name'] = $row[2];
                    header('Location: index.php?page=account');
                    exit;
                } elseif ($is_trainer) {
                    unset($_SESSION['form_data']);
                    unset($_SESSION['errors']);
    
                    $_SESSION['trainer_logged_in'] = true;
                    $_SESSION['trainer_id'] = $trainer_id;
                    header('Location: index.php?page=trainer/account');
                    exit;
                } elseif ($is_admin) {
                    unset($_SESSION['form_data']);
                    unset($_SESSION['errors']);
    
                    $_SESSION['admin_logged_in'] = true;
                    header('Location: index.php?page=admin/training-schedule');
                    exit;
                } else {
                    $_SESSION['errors']['login_error'] = "Неправельно введены почта или пароль";
                    header("Location: $previous_url");
                    exit;
                }
            } else {
                header("Location: $previous_url");
                exit;
            }
        }
    }

    function validation($mail, $password) {
        $errors_flag = false;
        $_SESSION['errors'] = [
            'mail_error' => null,
            'password_error' => null,
            'login_error' => null,
        ];

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

        if (empty($password)) {
            $_SESSION['errors']['password_error'] = "Заполните это поле";
            $errors_flag = true;
        } elseif (!preg_match('/[а-яА-Яa-zA-Z]+/', $password) || !preg_match('/\d/', $password)) {
            $_SESSION['errors']['password_error'] = "Пароль должен содержать хотя бы одну букву и цифру";
            $errors_flag = true;
        } elseif (mb_strlen($password) < 5 || mb_strlen($password) > 30) {
            $_SESSION['errors']['password_error'] = 'Пароль может содержать от 5 до 30 символов';
            $errors_flag = true;
        }

        return $errors_flag;
    }

    function clear_session() {
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            unset($_SESSION['form_data']);
            unset($_SESSION['errors']);
        }
        return;
    }
?>