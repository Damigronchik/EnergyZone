<?php
$scripts[] = JS_PATH . 'validateCheckboxes.js';
$scripts[] = JS_PATH . 'signup_for_training.js';
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
        <h2 class="subscriptions__title">Абонементы</h2>
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
                    <div class="card">
                        <form method='post' action="index.php?action=admin/subscription" onsubmit="return validateCheckboxes(event)">
                            <input type="hidden" name="subscription_id" value="<?= $row[0] ?>">
                            <div class="card__info">
                                <input type="text" class="card__title text-black" name="title" value="<?= $row[1] ?>">
                                <label>
                                    <span>Цена за месяц</span>
                                    <input type="number" class="card__price text-black" name="price" value="<?= $row[2] ?>" min="10" max="999" data-text="<?= $row[2] ?> BYN">
                                    <span>BYN</span>
                                </label>
                                <div class="card__line"></div>
                            </div>
                            <div class="card__advantages">
                                <?php
                                if (isset($row[3])):
                                    $all_advantages = explode("\n", $row[3]);
                                    foreach ($all_advantages as $advantage):
                                    ?>
                                    <div class="card__advantage">
                                        <img src="<?= IMG_PATH ?>abonement_advantage_icon.png" alt="icon">
                                        <p><?= $advantage ?></p>
                                    </div>
                                    <?php endforeach;
                                endif; 
                                foreach ($all_programs as $program): ?>
                                    <label>
                                        <input class="mr-1" type="checkbox" name="training_programs[]" value="<?= $program ?>" 
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
        <button type="button" id="addButton">Добавить абонемент</button>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Создать абонемент</h2>
                <form class="flex flex-col gap-1" action="index.php?action=admin/subscription" method="post" onsubmit="return validateCheckboxes(event)">
                    <input class="text-black" type="text" name="title" placeholder="Название" required>
                    <span>Цена за месяц</span>
                    <input type="number" class="card__price text-black" name="price" min="10" max="999">
                    <span>BYN</span>
                    <div class="flex flex-col gap-1">
                        <?php foreach ($all_programs as $program): ?>
                            <label>
                                <input class="mr-1" type="checkbox" name="training_programs[]" value="<?= $program ?>"><?= $program ?>
                            </label>
                        <?php endforeach ?>
                        <span class="checkbox-error" style="color: red; display: none;">Выберите хотя бы одну программу!</span>                    
                    </div>
                    <button type="submit" name="operation" value="create">Создать</button>
                </form>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>

    <div class="modal-window" id="deleteWindow">
        <div class="modal-window__content" id="deleteContent">
            <h2 class="modal-window__title">Удалить абонемент?</h2>
            <p class="modal-window__description">Вы уверены что хотите удалить абонемент?</p>
            <form class="flex flex-col gap-1" action="index.php?action=admin/subscription" method="post">
                <input type="hidden" name="subscription_id" value="<?= $_POST['subscription_id'] ?>">
                <button type="submit" name="operation" value="delete">Удалить</button>
            </form>
            <button type="button" id="cancelButton">Отмена</button>
            <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>

<?php
if (isset($_POST['subscription_id']) && isset($_POST['is_delete'])) {
    echo "<script>document.getElementById('deleteWindow').style.display = 'flex'</script>";
}
?>