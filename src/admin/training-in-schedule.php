<?php
$operation = $_POST['operation'] ?? null;
$id = $_POST['training_in_schedule_id'] ?? null;
$week_day = $_POST['week_day'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$name = $_POST['training_name'] ?? null;
$trainer_id = $_POST['trainer_id'] ?? null;
$people_amount = $_POST['people_amount'] ?? null;
$price = $_POST['price'] ?? null;

switch($operation) {
    case 'create':
        $create_query = "INSERT INTO new_schedule(week_day, start_time, training_name, trainer_id, people_amount, remaining_amount, price)
            VALUES ('{$week_day}', '{$start_time}', '{$name}', $trainer_id, $people_amount, $people_amount, $price)";
        $query = mysqli_query($link, $create_query);
        $new_id = mysqli_insert_id($link);
        $create_query2 = "INSERT INTO all_training_schedule SELECT * FROM new_schedule WHERE id = $new_id";
        $query2 = mysqli_query($link, $create_query2);
        if ($query) {
            $_SESSION['status'] = 'success';
            $_SESSION['operation'] = $operation;
        } else {
            $_SESSION['status'] = 'fail';
        }
        break;    
    case 'update':
        $update_query = "UPDATE new_schedule SET
            training_name = '$name',
            trainer_id = $trainer_id,
            people_amount = $people_amount,
            price = $price
            WHERE id = $id";
        $query = mysqli_query($link, $update_query);
        $update_query2 = "UPDATE all_training_schedule SET
            training_name = '$name',
            trainer_id = $trainer_id,
            people_amount = $people_amount,
            price = $price
            WHERE id = $id";
        $query2 = mysqli_query($link, $update_query2);
        if ($query) {
            $_SESSION['status'] = 'success';
            $_SESSION['operation'] = $operation;
        } else {
            $_SESSION['status'] = 'fail';
        }
        break;
    case 'delete':
        $delete_query = "DELETE FROM new_schedule WHERE id = {$id}";
        $query = mysqli_query($link, $delete_query);
        if ($query) {
            $_SESSION['status'] = 'success';
            $_SESSION['operation'] = $operation;
        } else {
            $_SESSION['status'] = 'fail';
        }
        break;    
    default:
        break;
}

header('Location: index.php?page=admin/training-schedule&mode=new');
?>