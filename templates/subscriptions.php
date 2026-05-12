<?php
$scripts[] = JS_PATH . 'signup_for_training.js';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('successWindow').style.display = 'flex'
        })</script>";
    } elseif ($_GET['status'] == 'notLog') {
        echo "<script>document.getElementById('logModalWindow').style.display = 'flex'</script>";
    }
}

$all_programs_result = mysqli_query($link, "SELECT name FROM training_programs");
$all_programs = getPrograms($all_programs_result, 'name');

$mail = $_SESSION['user_mail'] ?? null;
$sub_ids = [];
if ($mail != null) {
    $subs_result = mysqli_query($link, "SELECT subscription_id FROM user_subscriptions WHERE user_mail = '$mail'");
    while ($row = mysqli_fetch_assoc($subs_result)) {
        array_push($sub_ids, $row['subscription_id']);
    }
}

function getPrograms($result, $field_name) {
    $programs = [];
    $row = mysqli_fetch_assoc($result);
    while ($row) {
        array_push($programs, $row[$field_name]);
        $row = mysqli_fetch_assoc($result);
    }
    return $programs;
}

function getPrice($price, $period) {
    $current_price = $price * $period * 0.9;
    return round($current_price);
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

                    if (in_array($row[0], $sub_ids)) {
                        continue;
                    }

                    $one_month_price = $row[2];
                    $sub_result = mysqli_query($link, "SELECT training_name FROM subscription_programs WHERE subscription_id = $row[0]");
                    $sub_programs = getPrograms($sub_result, 'training_name');
                    ?>
                    <form class="card" action="index.php?action=user/get_sub" method="post">
                        <input type="hidden" name="subscription_id" value="<?= $row[0] ?>">
                        <div class="card__main-info">
                            <h6 class="card__title"><?= $row[1] ?></h6>
                            <select class="card__input" name="period">
                                <option class="text-black" value="1" selected>1 месяц - <?= $one_month_price ?>руб.</option>
                                <option class="text-black" value="3">3 месяца - <?= getPrice($one_month_price, 3) ?>руб.</option>
                                <option class="text-black" value="6">6 месяцев - <?= getPrice($one_month_price, 6) ?>руб.</option>
                            </select>
                            <div class="card__line"></div>
                        </div>
                        <div class="card__info">
                            <p class="card__description"><?= $row[3] ?></p>
                            <div class="card__advantages">
                                <span class="card__include">Абонемент включает:</span>
                                <?php
                                foreach ($sub_programs as $program): ?>
                                    <div class="card__advantage">
                                        <img src="<?= IMG_PATH ?>abonement_advantage_icon.png" alt="icon">
                                        <p><?= $program ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <?php if (!isset($_SESSION['trainer_logged_in'])): ?>
                            <button type="submit" class="card__button" name="operation" value="update">Приобрести</button>
                        <?php endif; ?>
                    </form>
                <?php endfor;
            endif; ?>
        </div>
    </section>    
    
    <div class="modal-window" id="successWindow">
        <div class="modal-window__content" id="successContent">
            <h2 class="modal-window__title">Абонемент приобретен!</h2>
            <p class="modal-window__description">Абонемент успешно приобретен. Увидеть активные абонементы можно в личном кабинете.</p>
            <div class="modal-window__buttons">
                <a class="modal-window__account" href="index.php?page=account">Перейти в личный кабинет</a>
            </div>
            <button class="modal-window__close-icon" id="goSchedule"><img src="<?= IMG_PATH ?>close_icon.png" alt="close_icon"></button>
        </div>
    </div>
</main>
