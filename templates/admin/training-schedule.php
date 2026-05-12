<?php 
$scripts[] = JS_PATH . 'trainersByProgram.js';
$scripts[] = JS_PATH . 'deleteWindow.js'; // перенсти в modalWindow.js
$scripts[] = JS_PATH . 'modalWindow.js';
$scripts[] = JS_PATH . 'adminSchedule.js';

require SRC_PATH . 'user/user-trainings_check.php';
require_once SRC_PATH . 'trainer/create_schedules.php';


$today = new DateTime('2026-04-14');
$day = $today->format('j');
$edited = false;
if ($day >= 14 && $day <= 15) {
    $mode = $_GET['mode'] ?? 'new';
    $edited = true;
} else {
    $mode = 'view';
}

if ($mode == 'new') {
    $all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
    $all_programs = getField($all_programs_result, 'name');
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
        $title = 'Произошла неизвестная ошибка';
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

function getTrainersByPrograms($link) {
    $trainersByProgram = [];
    $query = "SELECT p.name as program_name, t.id, t.name as trainer_name 
              FROM trainers t 
              JOIN trainer_programs tp ON t.id = tp.trainer_id 
              JOIN training_programs p ON tp.training_name = p.name";
    $result = mysqli_query($link, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $trainersByProgram[$row['program_name']][] = [
            'id' => $row['id'],
            'name' => $row['trainer_name']
        ];
    }
    return json_encode($trainersByProgram);
}
?>

<main>
    <?php if ($mode === 'view') {
        include 'training-schedule_view.php';
    } elseif ($mode === 'wishlist') {
        include 'training-schedule_wishlist.php';
    } elseif ($mode === 'new') {
        include 'training-schedule_new.php';
    } ?>

</main>

<script>const trainersData = <?= getTrainersByPrograms($link) ?>;</script>

<?php
if (isset($_SESSION['status'])) {
    unset($_SESSION['status']);
    unset($_SESSION['operation']);        
    echo "<script>document.getElementById('successWindow').style.display = 'flex'</script>";
}
?>