<?php

require '../framework/flight/autoload.php';
require '../framework/includes/loader/Config.php';

$db = require_once '../framework/includes/helper/QueryBuilder.php';
$page = require_once '../framework/includes/helper/QueryPaginator.php';
$v = require_once '../framework/includes/helper/Validator.php';
require_once '../framework/includes/helper/ApiHelper.php';
require_once '../framework/includes/helper/AttachedfileConf.php';
require_once '../framework/includes/helper/ScoringConf.php';

require '../framework/includes/helper/Utils.php';
require '../framework/includes/helper/AccessControl.php';

// load flight framework init
require '../framework/includes/loader/Init.php';

require 'app/templates/EmailFormat.php';
require 'app/config.php';

function linkTo($path)
{
    echo WEB_URL.$path;
}

$app->map('error', function(Throwable $ex) {
    // Handle error
    var_dump($ex, $ex->getMessage(), $ex->getFile(), $ex->getLine());
    exit;
});

$app->map('notFound', function() {
    // Handle not found
    echo '404, not found';
    exit;
});

$app->before('start', function() use ($control) {

    // check session if ajax request
    if (request()->ajax)
    {
        if (!isset($_SESSION['users']))
        {
            echo 'SESSION EXPIRED';
            exit;
        }
    }
    
    // check session token authentification
    if ($control != 'auth') 
    {
        if (!isset($_SESSION['users']) || $control == '')
            redirect('/auth/login');
    }
    else
    {
        // if users log to auth        
        $findLogin = strpos(request()->url, '/auth/login');
        $findSetPass = strpos(request()->url, '/auth/set_pass');

        if ($findLogin === 0 || $findSetPass === 0)
        {
            if (isset($_SESSION['users']))
                redirect('/dashboard/home');
        }
    }
});

$app->after('start', function() {
    
    if (!isViewLoaded())
    {
        $viewPath = substr(request()->url, 1);
        
        $fullPath = DOC_ROOT_ADMIN.app()->get('flight.views.path').$viewPath.'.php'; 

        // load view if custom view not loaded yet
        if (file_exists($fullPath))
            renderView(substr(request()->url, 1));
        else
            echo $viewPath.'.php not found';
    }
});

if ($control != '') 
{
    $filename = 'app/controllers/' . ucfirst($control) . 'Controller.php';
    
    if (file_exists($filename))
        require $filename;
}

$app->start();