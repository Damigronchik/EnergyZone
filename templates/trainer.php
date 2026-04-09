<?php
$id = $_POST['trainer_id'];
$trainer = mysqli_query($link, "SELECT * FROM trainers WHERE id = '$id'");

if ($trainer) {
    $row = mysqli_fetch_row($trainer);

    $birth = new DateTime($row[3]);
    $today = new DateTime('today');
    $age = $birth->diff($today);
}

$all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
$all_programs = getPrograms($all_programs_result, 'name');

$trainer_programs_result = mysqli_query($link, "SELECT training_name FROM trainer_programs WHERE trainer_id = $id");
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
        <a class="flex gap-1 items-center" href="index.php?page=trainers-list"><img class="w-4 h-2" src="<?= IMG_PATH . 'arrow.png' ?>">Назад</a>

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
</main>