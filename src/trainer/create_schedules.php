<?php
$wish_schedule_result = mysqli_query($link, "SELECT id FROM wishlist_schedule");
$isset_wishlist = mysqli_num_rows($wish_schedule_result) > 0;
if (!$isset_wishlist) {
    $create_query = "INSERT INTO wishlist_schedule (id, week_day, start_time, training_name, trainer_id, people_amount)
        SELECT id, week_day, start_time, training_name, trainer_id, people_amount FROM training_schedule";
    mysqli_query($link, $create_query);
}


$new_schedule_result = mysqli_query($link, "SELECT id FROM new_schedule");
$isset_new_schedule = mysqli_num_rows($new_schedule_result) > 0;
if (!$isset_new_schedule) { 
    $create_query = "INSERT INTO new_schedule
        SELECT * FROM training_schedule";
    mysqli_query($link, $create_query);
}
?>