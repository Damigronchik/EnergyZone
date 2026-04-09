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
        $create_query = "INSERT training_schedule(week_day, start_time, training_name, trainer_id, people_amount, remaining_amount, price)
            VALUES ('{$week_day}', '{$start_time}', '{$name}', $trainer_id, $people_amount, $people_amount, $price)";
        mysqli_query($link, $create_query);
        break;    
    case 'update':
        $update_query = "UPDATE training_schedule SET
            training_name = '$name',
            trainer_id = $trainer_id,
            people_amount = $people_amount,
            price = $price
            WHERE id = $id";
        mysqli_query($link, $update_query);
        break;
    case 'delete':
        $delete_query = "DELETE FROM training_schedule WHERE id = {$id}";
        mysqli_query($link, $delete_query);
        break;    
    default:
        break;
}

header('Location: index.php?page=admin/training-schedule');
?>