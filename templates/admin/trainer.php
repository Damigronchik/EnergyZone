<?php
$scripts[] = JS_PATH . 'validateCheckboxes.js';
$scripts[] = JS_PATH . 'deleteWindow.js';

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
        <form class="flex gap-4" action="index.php?action=admin/trainer" method="post" enctype="multipart/form-data" onsubmit="return validateCheckboxes(event)">
            <img src="<?= IMG_PATH . $row[2] ?>">
            <div class="flex flex-col gap-1">
                <input class="text-black" type="text" name="name" value="<?= $row[1] ?>" required>
                <p>Возраст: <?= $age->y ?></p>
                <input class="text-black" type="date" name="birthday" value="<?= $row[3] ?>" required>
                <label>Новая фотография
                    <input class="text-black bg-white" type="file" name="photo" accept="image/*">
                </label>
                <textarea class="text-black" name="biography" required><?= $row[4] ?></textarea>
                <div class="flex flex-col gap-1">
                    <?php foreach ($all_programs as $program): ?>
                        <label>
                            <input class="mr-1" type="checkbox" name="training_programs[]" value="<?= $program ?>" 
                                <?= in_array($program, $trainer_programs) ? 'checked' : '' ?>><?= $program ?>
                        </label>
                    <?php endforeach ?>
                    <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
                </div>

                <div class="mt-4">
                    <input type="hidden" name="trainer_id" value="<?= $id ?>">
                    <button class="mr-2" type="submit" name="operation" value="update">Сохранить</button>
                </div>
            </div>
        </form>
        <button id="deleteButton">Удалить тренера</button>
    </section>

    <div class="modal-window" id="deleteWindow">
        <div class="modal-window__content" id="deleteContent">
            <h2 class="modal-window__title">Удалить тренера?</h2>
            <p class="modal-window__description">Вы уверены что хотите удалить тренера? Это повлечет за собой удаление его тренировок в расписании!</p>
            <form class="flex flex-col gap-1" action="index.php?action=admin/trainer" method="post">
                <input type="hidden" name="trainer_id" value="<?= $id ?>">
                <button type="submit" name="operation" value="delete">Удалить</button>
            </form>
            <button type="button" id="cancelButton">Отмена</button>
            <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>