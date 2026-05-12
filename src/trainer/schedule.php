<?php
$operation = $_POST['operation'] ?? null;
$id = $_POST['training_in_schedule_id'] ?? null;
$week_day = $_POST['week_day'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$name = $_POST['training_name'] ?? null;
$trainer_id = $_SESSION['trainer_id'];
$people_amount = $_POST['people_amount'] ?? null;

unset($_SESSION['status']);
unset($_SESSION['operation']);

switch($operation) {
    case 'create':
        $create_query = "INSERT wishlist_schedule(week_day, start_time, training_name, trainer_id, people_amount)
            VALUES ('{$week_day}', '{$start_time}', '{$name}', $trainer_id, $people_amount)";
        $query = mysqli_query($link, $create_query);
        if ($query) {
            $_SESSION['status'] = 'success';
            $_SESSION['operation'] = $operation;
        } else {
            $_SESSION['status'] = 'fail';
        }
        break;    
    case 'update':
        $update_query = "UPDATE wishlist_schedule SET
            training_name = '$name',
            people_amount = $people_amount
            WHERE id = $id";
        $query = mysqli_query($link, $update_query);
        if ($query) {
            $_SESSION['status'] = 'success';
            $_SESSION['operation'] = $operation;
        } else {
            $_SESSION['status'] = 'fail';
        }
        break;
    case 'delete':
        $trainings_count_result = mysqli_query($link, "SELECT id FROM wishlist_schedule WHERE trainer_id = $trainer_id");
        $count = mysqli_num_rows($trainings_count_result) > 5;
        if ($count) {
            $delete_query = "DELETE FROM wishlist_schedule WHERE id = {$id}";
            $query = mysqli_query($link, $delete_query);
            if ($query) {
                $_SESSION['status'] = 'success';
                $_SESSION['operation'] = $operation;
            } else {
                $_SESSION['status'] = 'fail';
            }
            break;    
        } else {
            $_SESSION['status'] = 'fail';
            $_SESSION['operation'] = $operation;
            break;
        }
    default:
        $_SESSION['status'] = 'fail';
        break;
}

header('Location: index.php?page=trainer/training-schedule');

?>