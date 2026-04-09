<?php $scripts[] = JS_PATH . 'signup_for_training.js'; ?>

<main>
    <section class="programs">
        <h1 class="programs__title">Программы тренировок</h1>
        <button type="button" id="addButton">Добавить тренировку</button>        
        <div class="programs__list">
        <?php
        $training_query = "SELECT name, image, description FROM training_programs";
        $training_result = mysqli_query($link, $training_query);

        if ($training_result):
            $rows = mysqli_num_rows($training_result);
            for ($i = 0; $i < $rows; $i++):
                $row = mysqli_fetch_row($training_result);
                ?>
                <form action="index.php?page=admin/training-program" method="post" class="card">
                    <input type="hidden" name="training_name" value="<?= $row[0] ?>">
                    <img class="card__back-img" src="<?= IMG_PATH . $row[1] ?>">
                    <div class="card__info">
                        <h4 class="card__name"><?= $row[0] ?></h4>
                        <p class="card__description"> <?= $row[2] ?></p>
                        <button class="card__learn-more">Подробнее</button>
                    </div>
                    <button class="card__button" type="submit"></button>
                </form>
            <?php endfor ?>
        <?php endif; ?>
        </div>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Создать тренировку</h2>
                <form class="flex flex-col gap-2" action="index.php?action=admin/training-program" method="post" enctype="multipart/form-data">
                    <input class="admin-title text-black" type="text" name="title" placeholder="Название" required>
                    <div class="flex gap-2">
                        <label>Фото для карточки:<br>
                            <input class="text-black bg-white" type="file" name="card_image" accept="image/*" required>
                        </label>
                        <label>Фото для хедера:<br>
                            <input class="text-black bg-white" type="file" name="header_image" accept="image/*" required>
                        </label>
                    </div>
                    <div class="info__block">
                        <h6 class="info__name">Описание</h6>
                        <textarea class="text-black" name="description" placeholder="Описание" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="info__button" name="operation" value="create">Создать</button>
                    </div>
                </form>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>