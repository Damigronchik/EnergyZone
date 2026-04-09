<?php
return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'secure' => 'tls',
        'auth' => true,
        'username' => 'energyzone.minsk.info@gmail.com',
        'password' => 'your-app-password',
    ],
    'from' => [
        'email' => 'your-email@gmail.com',
        'name' => 'Фитнес-центр',
    ],
    'to' => [
        'email' => 'admin@fitnes-centr.ru',    // Куда отправлять письма
        'name' => 'Администратор',
    ],
];