<main>
    <section class="programs">
        <h1 class="programs__title">Программы тренировок</h1>
        <!-- <img src="../image/services_back.png" alt=""> -->
        <div class="programs__list">
        <?php
        $training_query = "SELECT name, image, description FROM training_programs";
        $training_result = mysqli_query($link, $training_query);

        if ($training_result) {
            $rows = mysqli_num_rows($training_result);
            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_row($training_result);
                ?>
                <form action="index.php?page=training-program" method="post" class="card">
                    <input type="hidden" name="training_name" value="<?= $row[0] ?>">
                    <img class="card__back-img" src="<?= IMG_PATH . $row[1] ?>">
                    <div class="card__info">
                        <h4 class="card__name"><?= $row[0] ?></h4>
                        <p class="card__description"> <?= mb_substr($row[2], 0, 120) . '...' ?></p>
                    </div>
                    <button class="card__button" type="submit"></button>
                </form>
                <?php
            }
        }
        ?>
        </div>
    </section>
</main>