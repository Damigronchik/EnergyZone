<?php 
$scripts[] = JS_PATH . 'modalWindow.js'; 
$scripts[] = JS_PATH . 'signup_for_training.js'; 

require SRC_PATH . 'user/user-trainings_check.php';

$mode = $_GET['mode'] ?? 'current';

$unique_free_trainings = [];
if ($_SESSION['in_account']) {
    $user_mail = $_SESSION['user_mail'];
    $free_trainings = [];
    
    $sub_ids_result = mysqli_query($link, "SELECT subscription_id FROM user_subscriptions WHERE user_mail = '$user_mail'");
    $sub_ids_count = mysqli_num_rows($sub_ids_result);
    for ($i = 0; $i < $sub_ids_count; $i++) {
        $sub_id = mysqli_fetch_row($sub_ids_result)[0];
        $sub_trainings = mysqli_query($link, "SELECT training_name FROM subscription_programs WHERE subscription_id = $sub_id");

        while ($row = mysqli_fetch_assoc($sub_trainings)) {
            array_push($free_trainings, $row['training_name']);
        }
    }

    $unique_free_trainings = array_unique($free_trainings);
}

$today_weekday = date('N');
$weekday_as_number = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
$week = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];

$now = new DateTime();
$monday = clone $now;
$monday->modify('monday this week');

if ($monday->format('m') != (clone $monday)->modify('+13 days')->format('m')) {
    require_once SRC_PATH .  'trainer/create_schedules.php';
}

if ($monday->format('m') != (clone $monday)->modify('-1 week')->format('m')) {
    mysqli_query($link, "DELETE FROM training_schedule");
    mysqli_query($link, "INSERT INTO training_schedule SELECT * FROM new_schedule");
}
?>

<main>
    <?php if ($mode === 'current') {
        include 'training-schedule_current.php';
    } elseif ($mode === 'next') {
        include 'training-schedule_next.php';
    } ?>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['training_id_for_signup'])) {
    if ($_SESSION['in_account']) {
        $user_mail = $_SESSION['user_mail'];
        $training_id = $_POST['training_id_for_signup'];
        
        $user_train_query = "SELECT * FROM user_trainings WHERE user_mail = '$user_mail' AND id_train_schedule = $training_id";
        $user_train_result = mysqli_query($link, $user_train_query);

        if (mysqli_num_rows($user_train_result) == 0) {
            $hour = $_POST['hour'];
            $training_weekday = $_POST['training_weekday'];
            $training_day_index = array_search($training_weekday, $weekday_as_number);

            $current_datetime = clone $monday;
            $current_datetime->modify('+' . ($training_day_index-1) . ' days');
            $current_datetime->setTime($hour, 0, 0);
            $formatted_datetime = $current_datetime->format('Y-m-d H:i:s');

            $add_train_query = "INSERT INTO user_trainings (user_mail, id_train_schedule, training_datetime) VALUES ('$user_mail', $training_id, '$formatted_datetime')";
            $add_train_result = mysqli_query($link, $add_train_query);
            
            echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
            echo "<script>document.getElementById('successWindow').style.display = 'flex'</script>";
        } else {
            echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
            echo "<script>document.getElementById('failWindow').style.display = 'flex'</script>";
            $_POST['training_id_for_signup'] = null;
        }
    } else {
        echo "<script>document.getElementById('modalWindow').style.display = 'none'</script>";
        echo "<script>document.getElementById('logModalWindow').style.display = 'flex'</script>";
    }
}
?>