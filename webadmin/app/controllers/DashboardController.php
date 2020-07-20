<?php

$app->route('GET /dashboard/index', function () {
    
    setOut([
        // 'totalProperty' => $totalProperti->total,
        // 'totalUser' => $totalUser->total,
        // 'latestProperty' => $latestProperty,
        // 'latestVisitor' => $latestVisitor,
        'first' => 1,
        'second' => 0,
        'pageTitle' => 'Dashboard'
    ]);

    renderView('dashboard/index');
    // render('dashboard/index');

});