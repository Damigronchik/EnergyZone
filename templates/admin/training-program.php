<?php
$scripts[] = JS_PATH . 'deleteWindow.js';


$training_name = $_POST['training_name'];
$training_query = "SELECT * FROM training_programs WHERE name = '$training_name'";
$training = mysqli_query($link, $training_query);

if ($training) {
    $row = mysqli_fetch_row($training);
}


$trainers = mysqli_query($link, "SELECT trainer_id FROM trainer_programs WHERE training_name = '{$training_name}'");
$trainersId = getSubs($trainers, 'trainer_id');

$subs = mysqli_query($link, "SELECT subscription_id FROM subscription_programs WHERE training_name = '{$training_name}'");
$subsId = getSubs($subs, 'subscription_id');

function getSubs($result, $field_name) {
    $programs = [];
    $trainer_row = mysqli_fetch_assoc($result);
    while ($trainer_row) {
        array_push($programs, $trainer_row[$field_name]);
        $trainer_row = mysqli_fetch_assoc($result);
    }
    return $programs;
}
?>

<main>
    <section class="info">
        <form class="flex flex-col gap-2" action="index.php?action=admin/training-program" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_title" value="<?= $row[0] ?>">
            <input class="admin-title text-black" type="text" name="title" value="<?= $row[0] ?>" required>
            <div class="flex gap-2">
                <label>Фото для карточки:<br>
                    <input class="text-black bg-white" type="file" name="card_image" accept="image/*">
                </label>
                <label>Фото для хедера:<br>
                    <input class="text-black bg-white" type="file" name="header_image" accept="image/*">
                </label>
            </div>
            <div class="info__block">
                <h6 class="info__name">Описание</h6>
                <textarea class="text-black" name="description" required><?= $row[3] ?></textarea>
            </div>
            <div>
                <button type="submit" class="info__button" name="operation" value="update">Сохранить</button>
            </div>
        </form>
        <button class="info__button" id="deleteButton">Удалить</button>

        <div class="info__block">
            <h6 class="info__name">Тренера</h6>
            <ul class="info__text">
                <?php
                foreach ($trainersId as $id):
                    $trainer = mysqli_query($link, "SELECT name, photo FROM trainers WHERE id = $id");
                    if ($trainer):
                        $trainer_row = mysqli_fetch_row($trainer);
                        ?>
                        <form action="index.php?page=admin/trainer" method="post">
                            <button class="flex items-center gap-4" type="submit">
                                <input type="hidden" name="trainer_id" value="<?= $id ?>">
                                <img class="photo" src="<?= IMG_PATH . $trainer_row[1] ?>">
                                <p><?= $trainer_row[0] ?></p>
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="info__block">
            <h6 class="info__name">Абонементы, включающие эту программу</h6>
            <ul class="info__text">
                <form action="index.php?page=admin/subscriptions" method="post">
                    <?php
                    foreach ($subsId as $id):
                        $sub = mysqli_query($link, "SELECT name FROM subscriptions WHERE id = $id");
                        if ($sub):
                            $sub_row = mysqli_fetch_row($sub);
                            ?>
                            <p>"<?= $sub_row[0] ?>"</p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <button class="info__button" type="submit">Абонементы</button>
                </form>
            </ul>
        </div>
    </section>

    <section class="other-programs">
        <h2 class="other-programs__title">Другие программы тенировок</h2>
        <div class="other-programs__cards">
        <?php
        $training_query = "SELECT name, image FROM training_programs LIMIT 3";
        $training = mysqli_query($link, $training_query);

        if ($training) {
            $rows = mysqli_num_rows($training);
            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_row($training);
                ?>
                <form action="index.php?page=admin/training-program" method="post" class="card">
                    <input type="hidden" name="training_name" value="<?= $row[0] ?>">
                    <img class="card__background-image" src="<?= IMG_PATH . $row[1] ?>">
                    <h4 class="card__title" id="cardTitle"><?= $row[0] ?></h4>
                    <button class="card__button" type="submit"></button>
                </form>
                <?php
            }
        }
        ?>
        </div>
    </section>

    <div class="modal-window" id="deleteWindow">
        <div class="modal-window__content" id="deleteContent">
            <h2 class="modal-window__title">Удалить тренировку?</h2>
            <p class="modal-window__description">Вы уверены что хотите удалить тренировку? Это повлечет за собой их удаление в расписании!</p>
            <form class="flex flex-col gap-1" action="index.php?action=admin/training-program" method="post">
                <input type="hidden" name="old_title" value="<?= $row[0] ?>">
                <button type="submit" name="operation" value="delete">Удалить</button>
            </form>
            <button type="button" id="cancelButton">Отмена</button>
            <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>