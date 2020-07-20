<?php

use flight\Engine;
$app = new Engine();
$app->set('flight.views.path', 'app/views/');
$app->set('flight.log_errors', true);

#region 2. Default event message (form message status)
if (!isset($_SESSION['messages']))
{
    $_SESSION['messages'] = [
        'isError' => false,
        'isSuccess' => false,
        'isWarning' => false,
        'isInfo' => false,
        'errorMessage' => '',
        'successMessage' => '',
        'warningMessage' => '',
        'infoMessage' => ''
    ];
}

function getMessages() 
{ 
    return $_SESSION['messages']; 
}

function setMessages($param) 
{
    foreach ($param as $i => $value)
        $_SESSION['messages'][$i] = $value;
}

function cleanMessages()
{
    unset($_SESSION['messages']);
    unset($_SESSION['data']);
}
#endregion

#region 3. Flight extends default framework features
function app() 
{ 
    return $GLOBALS['app']; 
}

function request() 
{ 
    return app()->request();
}

function redirect($path)
{
    app()->redirect($path);
    exit;
}

// data output for render view
$out = []; 

function post() { return $GLOBALS['app']->request()->data; }
function get() { return $GLOBALS['app']->request()->query; }

function db() { return $GLOBALS['db']; }
function out() { return $GLOBALS['out']; }
function v() { return $GLOBALS['v']; }
function page() { return $GLOBALS['page']; }

function setOut($param) 
{
    $GLOBALS['out'] = $param;
    $GLOBALS['out']['messages'] = getMessages();
    cleanMessages();
}

function contentJson()
{
    header("Content-Type: application/json");
    $GLOBALS['viewLoaded'] = true;
}
#endregion

#region 4. View template helper
$viewLoaded = false;

// single render template file
function render($pathView, $params = null)
{
    if ($params == null) $params = out();

    app()->render($pathView, $params);

    $GLOBALS['viewLoaded'] = true;
}

// render from header & footer template
function renderView($pathView, $params = null)
{   
    if ($params == null) $params = out();

    // to remove url parameter, so we can do auto view & js render
    request()->url = '/'.$pathView;

    if (!$GLOBALS['viewLoaded'])
    {
        $GLOBALS['viewLoaded'] = true;
        app()->render('sidebar.php', $params);
        app()->render('header.php', $params);
        app()->render($pathView);
        app()->render('footer.php');
        app()->render($pathView."js");
    }
}

function isViewLoaded() 
{
    return $GLOBALS['viewLoaded'];
}

// control the url file path
$url = explode('/', request()->url);
$control = $url[1];
#endregion