<section class="schedule">
    <h1 class="schedule__title">Расписание занятий</h1>

    <div class="schedule__change">
        <a href="#" class="schedule__change-button schedule__change-button_disable">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
        <p class="schedule__change-text">Новое расписание</p>
        <a href="index.php?page=admin/training-schedule&mode=wishlist" class="schedule__change-button">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
    </div>
    <span class="schedule__change-description">Это новое расписание на неделю. Расписание вступит в силу со следующего месяца.</span>
    
    <table class="schedule-table">
        <thead>
            <tr>
                <th class="schedule-table__weekday"><img src="<?= IMG_PATH ?>time_icon.png" alt="time-icon"></th>
                <?php 
                $weekday_as_number = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
                $week = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];

                $now = new DateTime();
                $monday = clone $now;
                $monday->modify('monday this week');

                for ($i = 1; $i < 8; $i++) {
                    $current_day = clone $monday;
                    $current_day->modify('+' . ($i - 1) . ' days');

                    if ($current_day->format('d.m') < $now->format('d.m')) {
                        $current_day->modify('+1 week');
                    }

                    $weekday_class = 'class="schedule-table__weekday"';
                    echo '<th ' . $weekday_class . '>' . $week[$i-1] . '</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($hour = 9; $hour < 22; $hour++) {
                echo '<tr>';
                echo '<td class="schedule-table__cell">' . $hour . ':00</td>';
                
                for ($i = 1; $i < 8; $i++) {
                    $cell_class = 'class="schedule-table__cell"';
                    $is_training = False;

                    $training_query = "SELECT ats.* FROM all_training_schedule ats 
                        INNER JOIN new_schedule s ON s.id = ats.id
                        WHERE ats.week_day = '{$weekday_as_number[$i]}'
                        AND ats.start_time = $hour";
                    $training_result = mysqli_query($link, $training_query);
                    $training_result = mysqli_query($link, $training_query);
                    $training = mysqli_fetch_row($training_result);
                    if ($training) {
                        $trainer_id = $training[4];
                        $trainer_name_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                        $trainer_name = mysqli_fetch_row($trainer_name_result)[0];
                        ?>

                        <td <?= $cell_class ?>>
                            <button type="button" 
                                    class="active-training"
                                    data-mode="<?= $mode ?>"
                                    data-training-id="<?= $training[0] ?>"
                                    data-training-name="<?= $training[3] ?>"
                                    data-trainer-name="<?= $trainer_name ?>"
                                    data-people-amount="<?= $training[5] ?>"
                                    data-price="<?= $training[7] ?>">
                                <?= $training[3] ?>
                            </button>
                        </td>

                        <?php
                        $is_training = True;
                    }
                    if (!$is_training): ?>
                        <td <?= $cell_class ?>>
                            <button type="button"
                                    class="new-training"
                                    data-weekday="<?= $weekday_as_number[$i] ?>"
                                    data-hour="<?= $hour ?>">
                                Добавить
                            </button>
                        </td>
                    <?php endif;
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</section>

<div class="modal-window" id="modalWindow">
    <form class="modal-window__content" id="modalContent" action="index.php?action=admin/training-in-schedule" method="post">
        <h2 class="modal-window__title" id="modalTitle">Изменить занятие</h2>
        <label for="trainingSelect" class="modal-window__label">Программа тренировки
            <select name="training_name" class="modal-window__input" id="trainingSelect" required>
                <option class="modal-window__option" value="none" selected disabled>Выберите занятие</option>
                <?php
                foreach ($all_programs as $program) {
                    echo '<option value="' . $program . '" class="modal-window__option">' . $program . '</option>';
                }
                ?>
            </select>
        </label>
        <label class="modal-window__label" for="trainerSelect">Тренер
            <select class="modal-window__input" name="trainer_id" id="trainerSelect" required>
                <option class="text-black" value="" selected disabled>Сначала выберите занятие</option>
            </select>
        </label>

        <label class="modal-window__label" for="modalPlaces">Количество людей
            <input class="modal-window__input" id="modalPlaces" type="number" name="people_amount" min="1" max="30" placeholder="Введите количество людей" required>
        </label>
        <label class="modal-window__label" for="modalPrice">Стоимость
            <input class="modal-window__input" id="modalPrice" type="number" name="price" min="1" max="30" placeholder="Введите стоимость занятия" required>
        </label>

        <input type="hidden" name="training_in_schedule_id" id="modalTrainingId" value="">
        <input type="hidden" name="week_day" id="weekDay" value="">
        <input type="hidden" name="start_time" id="startTime" value="">

        <div class="modal-window__buttons" id="updateMode" style="display: none;">
            <button class="modal-window__signup" name="operation" value="update">Сохранить</button>
            <button type="button" class="modal-window__goback" id="sureDelete" name="operation" value="delete" formnovalidate>Удалить</button>
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

<div class="modal-window" id="deleteWindow">
    <div class="modal-window__content" id="deleteContent">
        <h2 class="modal-window__title">Удаление тренировки</h2>
        <p class="modal-window__description">Вы уверены что хотите удалить тренировку в расписании?</p>
        <form class="modal-window__buttons" action="index.php?action=admin/training-in-schedule" method="post">
            <input type="hidden" name="training_in_schedule_id" id="trainingIdForDelete" value="">
            <button class="modal-window__signup" type="submit" name="operation" value="delete">Удалить</button>
            <button class="modal-window__goback" type="button" id="cancelButton">Отмена</button>
        </form>
        <button class="modal-window__close-icon" id="closeIconButton"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
    </div>
</div>
