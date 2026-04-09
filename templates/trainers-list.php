<?php $scripts[] = JS_PATH . 'signup_for_training.js'; ?>

<main>
    <section class="programs">
        <h1 class="programs__title">Тренера</h1>
        <div class="list">
        <?php
        $trainer_query = "SELECT id, name, photo FROM trainers";
        $trainer_result = mysqli_query($link, $trainer_query);

        if ($trainer_result):
            $rows = mysqli_num_rows($trainer_result);
            for ($i = 0; $i < $rows; $i++):
                $row = mysqli_fetch_row($trainer_result);
                ?>
                <form action="index.php?page=trainer" method="post">
                    <button class="flex items-center gap-4" type="submit">
                        <input type="hidden" name="trainer_id" value="<?= $row[0] ?>">
                        <img class="photo" src="<?= IMG_PATH . $row[2] ?>">
                        <p><?= $row[1] ?></p>
                    </button>
                </form>
            <?php endfor ?>
        <?php endif; ?>
        </div>
    </section>
</main>