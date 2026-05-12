<?php 
$scripts[] = JS_PATH . 'signup_for_training.js';
$scripts[] = JS_PATH . 'validateCheckboxes.js';
$scripts[] = JS_PATH . 'modalWindow.js';

$all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
$all_programs = getPrograms($all_programs_result, 'name');

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
    <section class="programs">
        <h1 class="programs__title">Тренеры</h1>

        <div class="list">
        <?php
        $trainer_query = "SELECT id, name, photo FROM trainers";
        $trainer_result = mysqli_query($link, $trainer_query);

        if ($trainer_result):
            $rows = mysqli_num_rows($trainer_result);
            for ($i = 0; $i < $rows; $i++):
                $row = mysqli_fetch_row($trainer_result);
                ?>
                <form action="index.php?page=admin/trainer" method="post">
                    <button class="flex items-center gap-4" type="submit">
                        <input type="hidden" name="trainer_id" value="<?= $row[0] ?>">
                        <img class="photo" src="<?= IMG_PATH . $row[2] ?>">
                        <p><?= $row[1] ?></p>
                    </button>
                </form>
            <?php endfor ?>
        <?php endif; ?>
        </div>
        <button class="add-button" type="button" id="addButton">Добавить тренера</button>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Создать тренера</h2>
                <form class="modal-window__form items-start" action="index.php?action=admin/trainer" method="post" enctype="multipart/form-data" onsubmit="return validateCheckboxes(event)">
                    <label class="modal-window__label">Имя и фамилия
                        <input class="modal-window__input" type="text" name="name" placeholder="Имя" required>
                    </label>
                    <label class="modal-window__label">Дата рождения
                        <input class="modal-window__input" type="date" name="birthday" required>
                    </label>
                    <label class="modal-window__label">Фото тренера
                        <input class="modal-window__input" type="file" name="photo" accept="image/*" required>
                    </label>
                    <label class="modal-window__label">Краткая биография
                        <textarea class="modal-window__input" name="biography" placeholder="Краткая биография" required></textarea>
                    </label>
                    <div class="flex flex-col gap-1">
                        <p class="modal-window__label">Специализация</p>
                        <?php foreach ($all_programs as $program): ?>
                            <label class="agreement">
                                <input class="checkbox" type="checkbox" name="training_programs[]" value="<?= $program ?>"><?= $program ?>
                            </label>
                        <?php endforeach ?>
                        <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
                    </div>
                    <button class="modal-window__goback" type="submit" name="operation" value="create">Создать тренера</button>
                </form>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>