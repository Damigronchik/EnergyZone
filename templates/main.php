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
    <section class="welcome">
        <h1 class="welcome__title">Фитнес-центр EnergyZone Тренируйтесь вместе с нами!</h1>
        <p class="welcome__label">Ваш путь к идеальной форме начинается здесь! Эффективные групповые занятия, удобное расписание и мотивация от лучших тренеров. Тренируйтесь с удовольствием!</p>
        <button class="welcome__button" id="goScheduleButton"><a href="index.php?page=training-schedule">Записаться на тренировку</a></button>
    </section>

    <section class="programs">
        <h2 class="programs__title">Наши программы тенировок</h2>
        <div class="programs__cards">
            <?php
            $training_query = "SELECT name, image FROM training_programs LIMIT 4";
            $subs_result = mysqli_query($link, $training_query);

            if ($subs_result):
                $rows = mysqli_num_rows($subs_result);
                for ($i = 0; $i < $rows; $i++):
                    $row = mysqli_fetch_row($subs_result);
                    ?>
                    <form action="index.php?page=training-program" method="post" class="card">
                        <input type="hidden" name="training_name" value="<?= $row[0] ?>">
                        <img class="card__background-image" src="<?= IMG_PATH . $row[1] ?>">
                        <h4 class="card__title" id="cardTitle"><?= $row[0] ?></h4>
                        <button class="card__button" type="submit"></button>
                    </form>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="advantages">
        <div class="advantage">
            <p class="advantage__number" data-text="10+">10+</p>
            <div class="advantage__line"></div>
            <p class="advantage__label" data-text="Лет опыта работы">Лет опыта работы</p>
        </div>
        <div class="advantage">
            <p class="advantage__number" data-text="50+">50+</p>
            <div class="advantage__line"></div>
            <p class="advantage__label" data-text="Различных тренажеров">Различных тренажеров</p>
        </div>
        <div class="advantage">
            <p class="advantage__number" data-text="15+">15+</p>
            <div class="advantage__line"></div>
            <p class="advantage__label" data-text="Тренеров с сертификатами">Тренеров с сертифакатами</p>
        </div>
    </section>

    <?php if (!isset($_SESSION['trainer_logged_in'])): ?>
        <section class="subscriptions" id="subscriptions">
            <h2 class="subscriptions__title">Абонементы</h2>
            <div class="subscriptions__cards">
                <?php
                $subs_result = mysqli_query($link, "SELECT * FROM subscriptions LIMIT 3");

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
                            <div class="card__info">
                                <h6><?= $row[1] ?></h6>
                                <select class="text-black" name="period">
                                    <option class="text-black" value="1" selected>1 месяц - <?= $one_month_price ?>руб.</option>
                                    <option class="text-black" value="3">3 месяца - <?= getPrice($one_month_price, 3) ?>руб.</option>
                                    <option class="text-black" value="6">6 месяцев - <?= getPrice($one_month_price, 6) ?>руб.</option>
                                </select>
                                <div class="card__line"></div>
                            </div>
                            <div class="card__advantages">
                                <?php
                                foreach ($sub_programs as $program): ?>
                                    <div class="card__advantage">
                                        <img src="<?= IMG_PATH ?>abonement_advantage_icon.png" alt="icon">
                                        <p><?= $program ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="submit" class="card__button" name="operation" value="update">Приобрести</button>
                        </form>
                    <?php endfor;
                endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     if ($_SESSION['in_account']) {
    //         $user_mail = $_SESSION['user_mail'];
    //         $subscr_id = $_POST['subscription_id'];
    //         $subscr_period = $_POST['subscription_period'];
    //         $month_amount = explode(' ', $subscr_period);
    //         $end_date = date('d.m.Y', strtotime("+$month_amount[0] month"));

    //         $user_subscr_query = "SELECT * FROM user_subscriptions WHERE user_mail = '$user_mail' AND id_subscriptions = $subscr_id";
    //         $user_subscr_result = mysqli_query($link, $user_subscr_query);
    //         if (mysqli_num_rows($user_subscr_result) == 0) {
    //             $add_subscr_query = "INSERT INTO user_subscriptions (user_mail, id_subscriptions, subscription_end_date) VALUES ('$user_mail', $subscr_id, '$end_date')";
    //             $add_subscr_result = mysqli_query($link, $add_subscr_query);
    
    //             header('Location: account.php');    
    //         }

    //         ob_end_flush();
    //     } else {
    //         echo "<script>logModalWindow.style.display = 'flex'</script";
    //     }
    // }
?>