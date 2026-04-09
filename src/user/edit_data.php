<?php
$operation = $_POST['operation'];
$mail = $_SESSION['user_mail'];

$errors_flag = false;

switch($operation) {
    case 'edit_data':
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);

        if (!$errors_flag) {
            $update_query = "UPDATE users SET
                name = '$name',
                phone = '$phone'
                WHERE mail = '$mail'";
            mysqli_query($link, $update_query);

            $_SESSION['user_mail'] = $mail;
            $_SESSION['user_name'] = $name;
        }

        break;
    case 'edit_password':
        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);

        if (!$errors_flag) {
            $result_check = mysqli_query($link, "SELECT password FROM users WHERE mail = '$mail'");
            $correct_password = mysqli_fetch_row($result_check)[0];
    
            if (password_verify($old_password, $correct_password)) {
                if (!$errors_flag) {
                    $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_query = "UPDATE users SET
                        password = '$hash_new_password'
                        WHERE mail = '$mail'";
                    mysqli_query($link, $update_query);
                }
            } else {
                $_SESSION['old_password_error'] = 'Неверный старый пароль';
                $_SESSION['edit_password_window'] = true;
            }
        }
        
        break;
    default:
        break;
}

header('Location: index.php?page=account');


// $errors_flag = false;
// $_SESSION['errors'] = [
//     'name_error' => null,
//     'mail_error' => null,
//     'phone_error' => null,
//     'password_error' => null,
//     'repeat-password_error' => null,
// ];

// switch($operation) {
//     case 'edit_data':
//         $name = trim($_POST['name']);
//         $mail = trim($_POST['mail']);
//         $phone = trim($_POST['phone']);

//         if (empty($name)) {
//             $_SESSION['errors']['name_error'] = "Заполните это поле";
//             $errors_flag = true;
//         } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 50) {
//             $_SESSION['errors']['name_error'] = "Имя может содержать от 2 до 50 символов";
//             $errors_flag = true;
//         } elseif (!preg_match('/^[a-zA-Zа-яА-Я]+$/u', $name)) {
//             $_SESSION['errors']['name_error'] = "Имя может содержать только русские или английские буквы";
//             $errors_flag = true;
//         }

//         if (empty($mail)) {
//             $_SESSION['errors']['mail_error'] = "Заполните это поле";
//             $errors_flag = true;
//         } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-z]{3,6}\.[a-z]{2,5}$/', $mail)) {
//             $_SESSION['errors']['mail_error'] = "Неккоректный адрес (Пример: example@gmail.com)";
//             $errors_flag = true;
//         } elseif (mb_strlen($mail) > 30) {
//             $_SESSION['errors']['mail_error'] = "Это поле не может содержать больше 30 символов";
//             $errors_flag = true;
//         }

//         if (empty($phone)) {
//             $_SESSION['errors']['phone_error'] = "Заполните это поле";
//             $errors_flag = true;
//         } elseif (!preg_match('/^(\+375|375)(\s|-|)?(29|25|33|44)(\s|-)?\d{3}(\s|-)?\d{2}(\s|-)?\d{2}$/', $phone)) {
//             $_SESSION['errors']['phone_error'] = "Некоректный номер телефона (Пример: +375 29 123-45-67)";
//             $errors_flag = true;
//         }

//         if (!$errors_flag) {
//             $update_query = "UPDATE users SET
//                 mail = '$mail',
//                 name = '$name',
//                 phone = '$phone'
//                 WHERE mail = '$mail'";
//             mysqli_query($link, $update_query);

//             $_SESSION['user_mail'] = $mail;
//             $_SESSION['user_name'] = $name;
//         }

//         break;
//     case 'edit_password':
//         $old_password = trim($_POST['old_password']);
//         $new_password = trim($_POST['new_password']);
//         $errors_flag = validatePassword($old_password);

//         if (!$errors_flag) {
//             $result_check = mysqli_query($link, "SELECT password FROM users WHERE mail = '$mail'");
//             $correct_password = mysqli_fetch_row($result_check)[0];
    
//             if (password_verify($old_password, $correct_password)) {
//                 $errors_flag = validatePassword($new_password);
//                 if (!$errors_flag) {
//                     $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
//                     $update_query = "UPDATE users SET
//                         password = '$hash_new_password'
//                         WHERE mail = '$mail'";
//                     mysqli_query($link, $update_query);
//                 }
//             }
//         }
        
//         break;
//     default:
//         break;
// }

// header('Location: index.php?page=account');

// $modal_type = $_POST['modal_type'];
// $_SESSION['form_data'] = [
//     'name' => $name,
//     'mail' => $mail,
//     'phone' => $phone,
//     'old_password' => $old_password,
//     'new_password' => $new_password,
//     'url' => $url,
//     'modal_type' => $modal_type,
// ];

// if (!$errors_flag) {
//     $query_check = "SELECT mail FROM users";
//     $result_check = mysqli_query($link, $query_check);
    
//     $isset_mail = false;
//     if ($result_check) {
//         $rows = mysqli_num_rows($result_check);

//         for ($i = 0; $i < $rows; $i++) {
//             $row = mysqli_fetch_row($result_check);
//             if ($row[0] == $mail) {
//                 $isset_mail = true;
//                 break;
//             }
//         }
//         mysqli_free_result($result_check);
//     }        

//     if ($isset_mail) {
//         $_SESSION['errors']['mail_error'] = "Эта почта уже занята!";
//         header("Location: $url");
//     } else {
//         $hash_password = password_hash($old_password, PASSWORD_DEFAULT);

//         $query_insert = "INSERT INTO users (mail, name, phone, old_password) 
//             VALUES ('$mail', '$name', '$phone', '$hash_password')";
//         $result_insert = mysqli_query($link, $query_insert);

//         unset($_SESSION['form_data']);
//         unset($_SESSION['errors']);

//         $_SESSION['in_account'] = True;
//         $_SESSION['user_mail'] = $mail;
//         $_SESSION['user_name'] = $name;
//         header('Location: index.php?page=account');
//     }
// } else {
//     header("Location: index.php?page=account");
// }

function validatePassword($password) {
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
?>