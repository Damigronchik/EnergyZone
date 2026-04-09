<?php
    $link = mysqli_connect("MySQL-8.0", "root", "", "energyzone") or die("Ошибка" . mysqli_error($link));
    if (!mysqli_set_charset($link, "utf8mb4")) {
        echo "Ошибка при загрузке набора символов utf8mb4 ";
        mysqli_error($link);
        exit();
    };
?>