<?php
$scripts[] = JS_PATH . 'validateCheckboxes.js';
$scripts[] = JS_PATH . 'modalWindow.js';
$scripts[] = JS_PATH . 'deleteWindow.js';


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
    <section class="subscriptions" id="subscriptions">
        <div class="subscriptions__header">
            <h2 class="subscriptions__title">Абонементы</h2>
            <button class="add-button" type="button" id="addButton">Добавить абонемент</button>
        </div>
        <div class="subscriptions__cards">
            <?php
            $subs_result = mysqli_query($link, "SELECT * FROM subscriptions");

            if ($subs_result):
                $rows = mysqli_num_rows($subs_result);
                for ($i = 0; $i < $rows; $i++):
                    $row = mysqli_fetch_row($subs_result);

                    $sub_result = mysqli_query($link, "SELECT training_name FROM subscription_programs WHERE subscription_id = $row[0]");
                    $sub_programs = getPrograms($sub_result, 'training_name'); 
                    ?>
                    <div class="card card_admin">
                        <form method='post' action="index.php?action=admin/subscription" onsubmit="return validateCheckboxes(event)">
                            <input type="hidden" name="subscription_id" value="<?= $row[0] ?>">
                            <div class="card__main-info">
                                <label class="card__label">Название
                                    <input type="text" class="card__input" name="title" value="<?= $row[1] ?>">
                                </label>
                                <label class="card__label">Цена за месяц (руб.)
                                    <input type="number" class="card__input" name="price" value="<?= $row[2] ?>" min="10" max="999">
                                </label>
                                <div class="card__line"></div>
                            </div>
                            <div class="card__info">
                                <label>Описание
                                    <textarea name="description" class="card__input card__input_big" max="100"><?= $row[3] ?></textarea>
                                </label>
                                <?php foreach ($all_programs as $program): ?>
                                    <label>
                                        <input class="checkbox" type="checkbox" name="training_programs[]" value="<?= $program ?>" 
                                            <?= in_array($program, $sub_programs) ? 'checked' : '' ?>><?= $program ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
    
                            <button type="submit" class="card__button" name="operation" value="update">Сохранить</button>
                        </form>
                        <form method="post">
                            <input type="hidden" name="subscription_id" value="<?= $row[0] ?>">
                            <input type="hidden" name="is_delete" value="<?= true ?>">
                            <button class="card__button">Удалить</button>
                        </form>
                    </div>
                <?php endfor;
            endif; ?>
        </div>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Создать абонемент</h2>
                <form class="flex flex-col gap-1" action="index.php?action=admin/subscription" method="post" onsubmit="return validateCheckboxes(event)">
                    <label class="modal-window__label">Название
                        <input class="modal-window__input" type="text" name="title" placeholder="Название" required>
                    </label>
                    <label class="modal-window__label">Цена за месяц
                        <input type="number" class="modal-window__input" name="price" min="10" max="999" placeholder="Цена в рублях" required>
                    </label>
                    <label class="modal-window__label">Описание
                        <textarea class="modal-window__input" name="description" max="150" placeholder="Краткое описание" required></textarea>
                    </label>
                    <div class="flex flex-col gap-1">
                        <p class="modal-window__label">Включает</p>
                        <?php foreach ($all_programs as $program): ?>
                            <label class="agreement">
                                <input class="mr-1 checkbox" type="checkbox" name="training_programs[]" value="<?= $program ?>"><?= $program ?>
                            </label>
                        <?php endforeach ?>
                        <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
                    </div>
                    <button class="modal-window__goback" type="submit" name="operation" value="create">Создать</button>
                </form>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>

    <div class="modal-window" id="deleteWindow">
        <div class="modal-window__content" id="deleteContent">
            <h2 class="modal-window__title">Удалить абонемент?</h2>
            <p class="modal-window__description">Вы уверены что хотите удалить абонемент?</p>
            <form class="modal-window__buttons" action="index.php?action=admin/subscription" method="post">
                <input type="hidden" name="subscription_id" value="<?= $_POST['subscription_id'] ?>">
                <button class="modal-window__goback" type="submit" name="operation" value="delete">Удалить</button>
                <button class="modal-window__homepage" type="button" id="cancelButton">Отмена</button>
            </form>
            <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>

<?php
if (isset($_POST['subscription_id']) && isset($_POST['is_delete'])) {
    echo "<script>document.getElementById('deleteWindow').style.display = 'flex'</script>";
}
?>