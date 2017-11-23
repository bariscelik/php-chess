<?php

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View\Engine\Volt;

require('algorithm.php');

session_start();

ini_set('display_errors',1);

// ******************** //
// **** EXCEPTIONS **** //
// ******************** //
set_exception_handler(function($e){
    switch (get_class($e)) {
        case 'TypeError':
            echo 'Type is not mismatch !';
            break;
        
        default:
            echo $e->getMessage();
            break;
    }
    echo '<br>Line'.$e->getLine();
});

// ******************** //
// **** INITIALIZE **** //
// ******************** //
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        __DIR__ . '/models/',
    ]
)->register();



$di = new FactoryDefault();

$di->set(
    'db',
    function () {
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql(
            [
                'host'     => 'localhost',
                'username' => 'chess',
                'password' => 'Yq2B2owz0kAycDEA',
                'dbname'   => 'chess',
            ]
        );

        $db->query('SET NAMES UTF8;')->execute();
        return $db;
    }
);






$app = new Micro($di);

// set alert css classes
$app->flashSession->setCssClasses(['error' => 'alert alert-danger', 'warning' => 'alert alert-warning']);

$app->setModelBinder(new \Phalcon\Mvc\Model\Binder());


// view member
$app['view'] = function () {
   
    $view = new \Phalcon\Mvc\View();

    $view->setViewsDir("./templates/");

    $view->registerEngines(
        [
            ".volt"  => "Phalcon\\Mvc\\View\\Engine\\Volt"
        ]
    );

    return $view;

};

// **** BEFORE **** //
$app->before(
    function () use ($app) {
        if (NULL === $app->session->get('auth') && $app->request->get('_url') != '/login' ) {

            $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();

            return false;
        }

        return true;
    }
);


// **** INDEX **** //
$app->map('/index',
    function () use ($app) {
        $app->view->render('index', 'index');
    }
);

// **** USER LOGIN **** //
$app->map('/login',
    function () use ($app) {

        if($app->session->get('auth') != NULL)
            $app->response->redirect('chess.php?_url=/boards', true)->sendHeaders();

        if($app->request->isPost())
        {
            // email validation
            if(!filter_var($app->request->getPost('email'), FILTER_VALIDATE_EMAIL))
            {
                $app->flashSession->error('Email is invalid!');
                $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();
                return false;
            }

            // passwords has to be greater than 5 characters
            if(strlen($app->request->getPost('password')) < 6)
            {
                $app->flashSession->error('Password is less than 6 character!');
                $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();
                return false;
            }

            // get user by email
            if(!($user = Users::findFirst(['email = ?1', 'bind' => [ 1 => $app->request->getPost('email') ]])))
            {
                $app->flashSession->error('There is no user by registered with this email!');
                $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();
                return false;
            }

            // check password
            if(!$app->security->checkHash($app->request->getPost('password'), $user->password))
            {
                $app->flashSession->error('Password that you entered is invalid!');
                $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();
                return false;
            }

            // user authorization
            $app->session->set('auth', $app->request->getPost('email'));

            // go to boards list
            $app->response->redirect('chess.php?_url=/boards', true)->sendHeaders();

        }else{
            $app->view->render('login', 'index');
        }

    }
);

// **** LOGOUT **** //
$app->map('/logout',
    function () use ($app) {

        $app->session->remove('auth');
        $app->response->redirect('chess.php?_url=/login', true)->sendHeaders();

    }
);

// **** FETCH **** //
$app->map('/fetch/{boardID}',
    function (int $boardID) use ($app) {

        $chess = new Algorithm($boardID);

        if($chess->board == false)
            return false;

        if($app->request->isPost())
            $chess->move(json_decode($app->request->getPost('cpos')), json_decode($app->request->getPost('tpos')));

        echo $chess->output();
    }
);

// **** SINGLE BOARD **** //
$app->map('/boards/{id}',
    function (int $id) use ($app) {
        $board = Boards::findFirst($id);
        
        $color = 'b';

        $result = ['TM' => $board->tm,
                   'SM' => $board->sm,
                   'tableHistory' => $board->history];


        $app->view->render('boards','single');
    }
);

// **** BOARDS **** //
$app->map('/boards',
    function () use ($app) {
        $boards = Boards::find();
        
        $color = 'b';

        /*$result = ['TM' => $board->tm,
                   'SM' => $board->sm,
                   'tableHistory' => $board->history];*/

        $app->view->boards = $boards;

        $app->view->render('boards','index');
    }
);


// **** NOT FOUND **** //
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'Aradığınız sayfa bulunamadı.';
});

// **** APPLICATION ERROR **** //
$app->error(
    function ($exception) {
        echo json_encode(
            [
                'code'    => $exception->getCode(),
                'status'  => 'error',
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine()
            ]
        );
    }
);

$app->handle();
