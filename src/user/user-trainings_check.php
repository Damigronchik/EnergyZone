<?php
$now = date('Y-m-d H:i:s');
    
$move_query = "INSERT INTO old_user_trainings (user_mail, id_train_schedule, training_datetime)
    SELECT user_mail, id_train_schedule, training_datetime
    FROM user_trainings
    WHERE training_datetime < '$now'";
mysqli_query($link, $move_query);
        
$delete_query = "DELETE FROM user_trainings WHERE training_datetime < '$now'";
mysqli_query($link, $delete_query);  

$schedule_result = mysqli_query($link, "SELECT id, people_amount FROM training_schedule");
$rows = mysqli_num_rows($schedule_result);
for ($i = 0; $i < $rows; $i++) {
    $row = mysqli_fetch_row($schedule_result);
    $id = $row[0];
    $user_trainings_result = mysqli_query($link, "SELECT COUNT(id_train_schedule) FROM user_trainings WHERE id_train_schedule = $id");
    $user_amount = mysqli_fetch_row($user_trainings_result)[0] ?? 0;
    $remaining_amount = $row[1] - $user_amount;
    mysqli_query($link, "UPDATE training_schedule SET remaining_amount = $remaining_amount WHERE id = $id");
}
?>