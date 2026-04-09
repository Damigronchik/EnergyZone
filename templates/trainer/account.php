<?php 
if (!isset($_SESSION['trainer_logged_in'])) {
    session_abort();
    header('Location: index.php?page=main');
}

require SRC_PATH . 'user/user-trainings_check.php'; 
require SRC_PATH . 'user/user-subs_check.php'; 


$trainer_id = $_SESSION['trainer_id'];
$trainer = mysqli_query($link, "SELECT * FROM trainers WHERE id = '$trainer_id'");

if ($trainer) {
    $row = mysqli_fetch_row($trainer);

    $birth = new DateTime($row[3]);
    $today = new DateTime('today');
    $age = $birth->diff($today);
}

$all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
$all_programs = getPrograms($all_programs_result, 'name');

$trainer_programs_result = mysqli_query($link, "SELECT training_name FROM trainer_programs WHERE trainer_id = $trainer_id");
$trainer_programs = getPrograms($trainer_programs_result, 'training_name');

function getPrograms($result, $field_name) {
    $programs = [];
    $row = mysqli_fetch_assoc($result);
    while ($row) {
        array_push($programs, $row[$field_name]);
        $row = mysqli_fetch_assoc($result);
    }
    return $programs;
}
?>

<main>
    <section>
        <h2 class="account__title">Мои данные</h2>
        <div class="flex gap-4">
            <img src="<?= IMG_PATH . $row[2] ?>">
            <div class="flex flex-col gap-1">
                <p><?= $row[1] ?></p>
                <p>Возраст: <?= $age->y ?></p>
                <p class="mt-2 mb-2"><?= $row[4] ?></p>
                <div class="flex flex-col gap-1">
                    <p>Преподаваемые программы тренировок:</p>
                    <?php foreach ($trainer_programs as $program): ?>
                    <form action="index.php?page=training-program" method="post">
                        <button class="flex items-center gap-4" type="submit">
                            <input type="hidden" name="training_name" value="<?= $program ?>">
                            <p><?= $program ?></p>
                        </button>
                    </form>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>

    <section class="trainings">
        <h1 class="trainings__title">Запланированные тренировки</h1>
        <div class="trainings__all">
            <?php
            $trainings_result = mysqli_query($link, "SELECT * FROM training_schedule WHERE trainer_id = $trainer_id");
            if ($trainings_result):
                $weekday_in_russian = ['Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday'  => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресенье'];
                $weekday_as_number = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
                $now = new DateTime();
                $monday = clone $now;
                $monday->modify('monday this week');

                $rows = mysqli_num_rows($trainings_result);
                for ($i = 0; $i < $rows; $i++):
                    $row = mysqli_fetch_row($trainings_result);

                    $current_day = clone $monday;
                    $current_weekday = $row[1];
                    $current_day->modify('+' . ($weekday_as_number[$current_weekday]-1) . ' days');

                    if ($current_day->format('d.m') < $now->format('d.m')) {
                        $current_day->modify('+1 week');
                    }

                    $train_datetime = $current_day->format('d.m.Y');
                    ?>
                    <div class="trainings__info">
                        <p class="trainings__label"><span class="trainings__name">Занятие:</span> <?= $row[3] ?></p>
                        <p class="trainings__label"><span class="trainings__name">Время:</span> <?= $row[2] ?>:00</p>
                        <p class="trainings__label"><span class="trainings__name">Дата:</span> <?= $train_datetime ?> (<?= $weekday_in_russian[$row[1]] ?>)</p>
                        <p class="trainings__label"><span class="trainings__name">Осталось мест:</span> <?= $row[6] ?> из <?= $row[5] ?></p>
                    </div>
                <?php endfor;
            endif; ?>
        </div>
    </section>

    <form action="index.php?action=user/logout" method="post">
        <button class="account__logout">Выйти из аккаунта</button>
    </form>
</main>

