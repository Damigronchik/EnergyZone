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
        <a class="flex gap-1 items-center" href="index.php?page=admin/trainers-list"><img class="w-4 h-2" src="<?= IMG_PATH . 'arrow.png' ?>">Назад</a>
    
        <form class="trainer" action="index.php?action=admin/trainer" method="post" enctype="multipart/form-data" onsubmit="return validateCheckboxes(event)">
            <img class="trainer__photo" src="<?= IMG_PATH . $row[2] ?>">
            <div class="trainer__info">
                <label class="trainer__label">Имя и фамилия
                    <input class="trainer__input" type="text" name="name" value="<?= $row[1] ?>" required>
                </label>
                <label class="trainer__label">Дата рождения
                    <input class="trainer__input" type="date" name="birthday" value="<?= $row[3] ?>" required>
                </label>
                <label class="trainer__label">Новая фотография
                    <input class="trainer__input" type="file" name="photo" accept="image/*">
                </label>
                <label class="trainer__label">Краткая биография
                    <textarea class="trainer__input trainer__input_big" name="biography" maxlength="650" required><?= $row[4] ?></textarea>
                </label>
            </div>
            <div class="trainer__info">
                <div class="flex flex-col gap-1">
                    <p class="trainer__label">Специализация</p>
                    <?php foreach ($all_programs as $program): ?>
                        <label class="agreement">
                            <input class="checkbox" type="checkbox" name="training_programs[]" value="<?= $program ?>" 
                                <?= in_array($program, $trainer_programs) ? 'checked' : '' ?>><?= $program ?>
                        </label>
                    <?php endforeach ?>
                    <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
                </div>

                <div class="flex flex-col gap-2">
                    <input type="hidden" name="trainer_id" value="<?= $id ?>">
                    <button class="save-button" type="submit" name="operation" value="update">Сохранить</button>
                    <button type="button" class="delete-button" id="deleteButton">Удалить тренера</button>
                </div>
            </div>
        </form>
    </section>

    <div class="modal-window" id="deleteWindow">
        <div class="modal-window__content" id="deleteContent">
            <h2 class="modal-window__title">Удалить тренера?</h2>
            <p class="modal-window__description">Вы уверены что хотите удалить тренера? Это повлечет за собой удаление его тренировок в расписании!</p>
            <form class="modal-window__buttons" action="index.php?action=admin/trainer" method="post">
                <input type="hidden" name="trainer_id" value="<?= $id ?>">
                <button class="modal-window__goback" type="submit" name="operation" value="delete">Удалить</button>
                <button class="modal-window__homepage" type="button" id="cancelButton">Отмена</button>
            </form>
            <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>