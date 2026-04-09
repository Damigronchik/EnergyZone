<?php
if ($_SESSION['in_account']) {
    $user_mail = $_SESSION['user_mail'];
    $id = $_POST['subscription_id'];
    $period = $_POST['period'];
    $today = new DateTime();
    
    $start_date = $today->format('Y-m-d');
    $end_date = clone $today;
    $end_date->modify('+' . $period . ' months')->format('Y-m-d');
    $end_date = $end_date->format('Y-m-d');
    
    
    $query = "INSERT INTO user_subscriptions(user_mail, subscription_id, start_date, end_date)
        VALUES ('$user_mail', $id, '$start_date', '$end_date')";
    mysqli_query($link, $query);
    
    header('Location: index.php?page=subscriptions&status=success');
} else {
    header('Location: index.php?page=subscriptions&status=notLog');
}
?>