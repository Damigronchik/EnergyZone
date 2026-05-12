<?php $scripts[] = JS_PATH . 'signup_for_training.js'; ?>
<?php $scripts[] = JS_PATH . 'modalWindow.js'; ?>

<main>
    <section class="programs">
        <div class="programs__header">
            <h1 class="programs__title">Программы тренировок</h1>
            <button class="add-button" type="button" id="addButton">Добавить тренировку</button>
        </div>
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
                        <p class="card__description"> <?= mb_substr($row[2], 0, 120) . '...' ?></p>
                    </div>
                    <button class="card__button" type="submit"></button>
                </form>
            <?php endfor ?>
        <?php endif; ?>
        </div>

        <div class="modal-window" id="modalWindow">
            <div class="modal-window__content" id="modalContent">
                <h2 class="modal-window__title">Создать тренировку</h2>
                <form class="modal-window__form" action="index.php?action=admin/training-program" method="post" enctype="multipart/form-data">
                    <label class="modal-window__label">Название тренировки
                        <input class="modal-window__input" type="text" name="title" placeholder="Название" required>
                    </label>
                    <label class="modal-window__label">Фото для карточки
                        <input class="modal-window__input" type="file" name="card_image" accept="image/*" required>
                    </label>
                    <label class="modal-window__label">Фото для хедера
                        <input class="modal-window__input" type="file" name="header_image" accept="image/*" required>
                    </label>
                    <label class="modal-window__label">Описание тренировки
                        <textarea class="modal-window__input modal-window__input_big" name="description" placeholder="Описание" max="300" required></textarea>
                    </label>
                    <div class="modal-window__buttons">
                        <button type="submit" class="modal-window__goback" name="operation" value="create">Создать трнеировку</button>
                    </div>
                </form>
                <button class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
            </div>
        </div>
    </section>
</main>