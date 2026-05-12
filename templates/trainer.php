<?php
$id = $_POST['trainer_id'];
$trainer = mysqli_query($link, "SELECT * FROM trainers WHERE id = '$id'");

if ($trainer) {
    $row = mysqli_fetch_row($trainer);

    $birth = new DateTime($row[3]);
    $today = new DateTime('today');
    $age = $birth->diff($today)->y;
    if ($age % 10 == 1) { $age_text = ' год'; }
    elseif ($age % 10 == 2 || $age % 10 == 3 || $age % 10 == 4) { $age_text = ' года'; }
    else { $age_text = ' лет'; }
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

        <div class="trainer">
            <img class="trainer__photo" src="<?= IMG_PATH . $row[2] ?>">
            <div class="trainer__info">
                <p class="trainer__name"><?=$row[1] ?> (<?= $age . $age_text ?>)</p>
                <p class="trainer__biography"><?= $row[4] ?></p>
                <div class="trainer__programs">
                    <p class="trainer-programs__title">Преподаваемые программы тренировок:</p>
                    <div class="trainer-programs__trainings">
                        <?php foreach ($trainer_programs as $program): ?>
                        <form action="index.php?page=training-program" method="post">
                            <button class="trainer-programs__training" type="submit">
                                <input type="hidden" name="training_name" value="<?= $program ?>">
                                <p><?= $program ?></p>
                            </button>
                        </form>
                        <?php endforeach ?>                    
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>