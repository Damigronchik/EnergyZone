<?php
$training_name = $_POST['training_name'];
$training_query = "SELECT * FROM training_programs WHERE name = '$training_name'";
$training_result = mysqli_query($link, $training_query);

if ($training_result) {
    $row = mysqli_fetch_row($training_result);
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
    <section class="program-header">
        <img class="program-header__back-img" src="<?= IMG_PATH . $row[2] ?>">
        <h1 class="program-header__title"><?= $row[0] ?></h1>
    </section>

    <section class="info">
        <a class="flex gap-1 items-center" href="index.php?page=programs-list"><img class="w-4 h-2" src="<?= IMG_PATH . 'arrow.png' ?>">Назад</a>

        <div class="info__block">
            <h6 class="info__name">Описание</h6>
            <div class="flex flex-col">
                <?php
                $descr = explode("\n", $row[3]);
                foreach ($descr as $descr_row):
                ?>
                <p><?= $descr_row ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="info__block">
            <h6 class="info__name">Тренера</h6>
            <ul class="info__text">
                <?php
                foreach ($trainersId as $id):
                    $trainer = mysqli_query($link, "SELECT name, photo FROM trainers WHERE id = $id");
                    if ($trainer):
                        $trainer_row = mysqli_fetch_row($trainer);
                        ?>
                        <form action="index.php?page=trainer" method="post">
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
                <form action="index.php?page=subscriptions" method="post">
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
        $training_query = "SELECT name, image FROM training_programs";
        $training_result = mysqli_query($link, $training_query);

        if ($training_result) {
            $rows = mysqli_num_rows($training_result);
            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_row($training_result);
                ?>
                <form action="index.php?page=training-program" method="post" class="card">
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
</main>