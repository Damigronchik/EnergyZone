<?php
$train_schedule_id = $_POST['train_schedule_id'];
$user_train_id = $_POST['user_train_id'];
$user_rating = $_POST['rating'] ?? 5;

$rating_result = mysqli_query($link, "SELECT rating_sum, rating_count FROM training_schedule WHERE id = $train_schedule_id");
$rating_row = mysqli_fetch_row($rating_result);
$rating_sum = $rating_row[0];
$rating_count = $rating_row[1];

$new_sum = $rating_sum + $user_rating;
$new_count = $rating_count + 1;

mysqli_query($link, "UPDATE training_schedule SET
    rating_sum = $new_sum,
    rating_count = $new_count
    WHERE id = $train_schedule_id");

mysqli_query($link, "UPDATE old_user_trainings SET
    is_ratinged = true
    WHERE id = $user_train_id");

header('Location: index.php?page=account');
?>