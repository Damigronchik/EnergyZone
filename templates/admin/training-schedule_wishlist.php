<section class="schedule">
    <h1 class="schedule__title">Расписание занятий</h1>

    <div class="schedule__change">
        <a href="index.php?page=admin/training-schedule&mode=new" class="schedule__change-button">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
        <p class="schedule__change-text">Расписание тренеров</p>
        <a href="index.php?page=admin/training-schedule&mode=view" class="schedule__change-button">
            <img class="img-default" src="<?= IMG_PATH ?>round_arrow.png" alt="arrow">
            <img class="img-hover" src="<?= IMG_PATH ?>round_arrow_active.png" alt="arrow">
        </a>
    </div>
    <span class="schedule__change-description">Это расписание, составленное тренерами в соответствии с их пожеланиями.</span>

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
                        $trainer_name_result = mysqli_query($link, "SELECT name FROM trainers WHERE id = $trainer_id");
                        $trainer_name = mysqli_fetch_row($trainer_name_result)[0];
                        ?>

                        <td <?= $cell_class ?>>
                            <button type="button" 
                                    class="active-training"
                                    data-mode="<?= $mode ?>"
                                    data-training-name="<?= $training[3] ?>"
                                    data-trainer-name="<?= $trainer_name ?>"
                                    data-people-amount="<?= $training[5] ?>">
                                <?= $training[3] ?>
                            </button>
                        </td>
                        <?php $is_training = True;
                    }
                    if (!$is_training) {
                        echo '<td ' . $cell_class . '></td>';
                    }
                }
                echo '</tr>';
            }                
            ?>
        </tbody>
    </table>
</section>

<div class="modal-window" id="modalWindow">
    <div class="modal-window__content" id="modalContent">
        <h2 class="modal-window__title" id="modalTitle" data-text="Занятие">Занятие</h2>
        <p class="modal-window__description" id="modalTrainer">Тренер: </p>
        <p class="modal-window__description" id="modalPlaces">Количество мест: </p>
        
        <div class="modal-window__buttons">
            <button class="modal-window__goback" id="goBackButton">Вернуться к расписанию</button>
        </div>
        <button class="modal-window__close-icon" id="iconCloseButton">
            <img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon">
        </button>
    </div>
</div>
