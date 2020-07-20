<?php

$app->route('GET /user/index', function () {

    $users = db()->query("SELECT * FROM user")->execute()->getResult();    

    setOut([
        'first' => 2,
        'second' => 1,
        'pageTitle' => 'List User',
        'users' => $users
    ]);

    renderView('user/index');
});

$app->route('GET /user/create', function () {

    setOut([
        'first' => 2,
        'second' => 2,
        'pageTitle' => 'Create User'
    ]);

    renderView('user/create');
});

$app->route('POST /user/create', function () {

    setOut([
        'first' => 2,
        'second' => 2,
        'pageTitle' => 'Create User',
        'users' => $users
    ]);

    renderView('user/create');
});

