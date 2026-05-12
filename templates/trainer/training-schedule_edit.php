<section class="schedule">
    <h1 class="schedule__title">Расписание занятий</h1>

    <div class="schedule__change">
        <a href="#" class="schedule__change-button schedule__change-button_disable">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
        <p class="schedule__change-text">Новове расписание</p>
        <a href="index.php?page=trainer/training-schedule&mode=view" class="schedule__change-button">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
    </div>
    <span class="schedule__change-description">В этом расписание можно выставить занятия на неделю в соответствии с вашими пожеланиями. Это не означает, что все изменения будут учтены, но мы будем стараться! Изменения вступают в силу только со следующего месяца!</span>

    <table class="schedule-table">
        <thead>
            <tr>
                <th class="schedule-table__weekday"><img src="<?= IMG_PATH ?>time_icon.png" alt="time-icon"></th>
                <?php 
                $weekday_as_number = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
                $week = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
                
                for ($i = 1; $i < 8; $i++) {
                    $weekday_class = 'class="schedule-table__weekday"';
                    echo '<th ' . $weekday_class . '>' . $week[$i-1] . '</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $now_hour = date('H');
            for ($hour = 9; $hour < 22; $hour++) {
                echo '<tr>';
                echo '<td class="schedule-table__cell">' . $hour . ':00</td>';
                
                for ($i = 1; $i < 8; $i++) {
                    $cell_class = 'class="schedule-table__cell"';
                    $is_training = False;

                    $training_query = "SELECT * FROM wishlist_schedule WHERE week_day = '{$weekday_as_number[$i]}' AND start_time = $hour";
                    $training_result = mysqli_query($link, $training_query);
                    $training = mysqli_fetch_row($training_result);

                    if ($training) {
                        $trainer_id = $training[4];

                        if ($_SESSION['trainer_id'] == $trainer_id):
                            // if (in_array($training[0], $newIds)) {
                            //     $cell_class = 'class="schedule-table__cell schedule-table__cell_new"';
                            // }
                            ?>

                            <td <?= $cell_class ?>>
                                <button type="button" 
                                        class="active-training"
                                        data-mode="<?= $mode ?>"
                                        data-training-id="<?= $training[0] ?>"
                                        data-training-name="<?= $training[3] ?>"
                                        data-people-amount="<?= $training[5] ?>">
                                    <?= $training[3] ?>
                                </button>
                            </td>
                        <?php else: ?>
                                <td <?= $cell_class ?>>
                                    <span class="disable-training"><?= $training[3] ?></span>
                                </td>
                        <?php endif;
                        $is_training = True;
                    }
                    if (!$is_training) { ?>
                        <td <?= $cell_class ?>>
                            <button type="button"
                                    class="new-training"
                                    data-weekday="<?= $weekday_as_number[$i] ?>"
                                    data-hour="<?= $hour ?>">
                                Добавить
                            </button>
                        </td>
                    <?php
                    }
                }
                echo '</tr>';
            }                
            ?>
        </tbody>
    </table>
</section>

<div class="modal-window" id="modalWindow">
    <form class="modal-window__content" id="modalContent" action="index.php?action=trainer/schedule" method="post">
        <h2 class="modal-window__title" id="modalTitle">Изменить занятие</h2>
        <label for="trainingSelect" class="modal-window__label">Программа тренировки
            <select name="training_name" class="modal-window__input" id="trainingSelect" required>
                <option class="modal-window__input" value="none" selected disabled>Выберите занятие</option>
                <?php
                foreach ($all_programs as $program) {
                    echo '<option value="' . $program . '" class="text-black">' . $program . '</option>';
                }
                ?>
            </select>
        </label>
        <label class="modal-window__label" for="modalPlaces">Количество людей
            <input class="modal-window__input" id="modalPlaces" type="number" name="people_amount" min="1" max="30" placeholder="Введите количество людей" required>
        </label>

        <input type="hidden" name="training_in_schedule_id" id="modalTrainingId" value="">
        <input type="hidden" name="week_day" id="weekDay" value="">
        <input type="hidden" name="start_time" id="startTime" value="">

        <div class="modal-window__buttons" id="updateMode" style="display: none;">
            <button class="modal-window__signup" name="operation" value="update">Сохранить</button>
            <button class="modal-window__goback" name="operation" value="delete" formnovalidate>Удалить</button>
        </div>
        <div class="modal-window__buttons" id="createMode" style="display: none;">
            <button class="modal-window__signup" name="operation" value="create">Добавить</button>
        </div>                        
        <button type="button" class="modal-window__close-icon" id="iconCloseButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
    </form>
</div>

<div class="modal-window" id="successWindow">
    <div class="modal-window__content" id="successContent">
        <h2 class="modal-window__title"><?= $title ?></h2>
        <div class="modal-window__buttons">
            <button class="modal-window__goback" id="closeSuccess">Вернуться к расписанию</button>
        </div>
        <button class="modal-window__close-icon" id="closeSuccessIcon"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
    </div>
</div>

<div class="modal-window" id="failWindow">
    <div class="modal-window__content" id="failContent">
        <h2 class="modal-window__title"><?= $title ?></h2>
        <?php if (!empty($message)): ?>
            <p class="modal-window__description"><?= $message ?></p>
        <?php endif; ?>
        <div class="modal-window__buttons">
            <button class="modal-window__goback" id="closeFail">Вернуться к расписанию</button>
        </div>
        <button class="modal-window__close-icon" id="closeFailIcon"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>            
    </div>
</div>

<?php
?>