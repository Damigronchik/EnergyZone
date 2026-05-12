<?php 
if (!isset($_SESSION['trainer_logged_in'])) {
    session_abort();
    header('Location: index.php?page=main');
}

$scripts[] = JS_PATH . 'modalWindow.js'; 
$scripts[] = JS_PATH . 'trainerSchedule.js'; 

require SRC_PATH . 'user/user-trainings_check.php';

$today = new DateTime('2026-04-12');
$day = $today->format('j');
if ($day >= 11 && $day <= 13) {
    require_once SRC_PATH . 'trainer/create_schedules.php';
    $mode = $_GET['mode'] ?? 'wishlist';
    $edited = true;
} else {        
    $mode = 'view';
    $edited = false;
}

if ($mode === 'wishlist') {
    $trainer_id = $_SESSION['trainer_id'];
    $all_programs_result = mysqli_query($link, "SELECT training_name FROM trainer_programs WHERE trainer_id = $trainer_id");
    $all_programs = getField($all_programs_result, 'training_name');
    // $newIds = getNewIds($link);
}

if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] == 'success') {
        $operation = $_SESSION['operation'];
        switch ($operation) {
            case 'create':
                $title = 'Тренировка успешно добавлена';
                break;
            case 'update':
                $title = 'Тренировка успешно изменена';
                break;
            case 'delete':
                $title = 'Тренировка успешно удалена';
                break;
            default:
                $title = 'Изменение расписания успешно выполнено';
                break;
        }
    } elseif ($_SESSION['status'] == 'fail') {
        if (isset($_SESSION['operation'])) {
            $title = 'Удаление невозможно';
            $message = 'В расписании должно быть миниму 5 ваших тренировок!';
        } else {
            $title = 'Произошла неизвестная ошибка';
        }
    }
}

function getField($result, $field_name) {
    $values = [];
    $row = mysqli_fetch_assoc($result);
    while ($row) {
        array_push($values, $row[$field_name]);
        $row = mysqli_fetch_assoc($result);
    }
    return $values;
}

function getNewIds($link) {
    $query = "SELECT w.id 
              FROM wishlist_schedule w
              LEFT JOIN training_schedule s ON w.id = s.id
              WHERE s.id IS NULL";
    $result = mysqli_query($link, $query);
    $ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ids[] = $row['id'];
    }
    return $ids;
}
?>

<main>
    <?php if ($mode === 'view') {
        include 'training-schedule_view.php';
    } elseif ($mode === 'wishlist') {
        include 'training-schedule_edit.php';
    } ?>
</main>

<?php
if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] == 'success') {
        unset($_SESSION['status']);
        unset($_SESSION['operation']);        
        echo "<script>document.getElementById('successWindow').style.display = 'flex'</script>";
    } elseif ($_SESSION['status'] == 'fail') {
        unset($_SESSION['status']);
        unset($_SESSION['operation']);        
        echo "<script>document.getElementById('failWindow').style.display = 'flex'</script>";
    }
}
?>