<?php
require 'vendor/autoload.php';
date_default_timezone_set('America/New_York');

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true
);
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$app->get('/', function() use($app){
    $app->render('about.twig');
})->name('home');

$app->get('/contact', function() use($app){
    $app->render('contact.twig');
})->name('contact');

$app->post('/contact', function() use($app){
    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $msg = $app->request->post('msg');

    if(!empty($name) && !empty($email) && !empty($msg)) {
        $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $cleanMsg = filter_var($msg, FILTER_SANITIZE_STRING);
    } else {
        // msg the user that there was a problem
        $app->redirect('/contact');
    }

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('sender@example.com')
    ->setPassword('password');
    $mailer = \Swift_Mailer::newInstance($transport);

    $message = \Swift_Message::newInstance();
    $message->setSubject('Email From Our Website');
    $message->setFrom(array(
        $cleanEmail => $cleanName
    ));
    $message->setTo(array('receiver@example.com'));
    $message->setBody($cleanMsg);

    $result = $mailer->send($message);

    if($result > 0) {
        // send a message awknowleding user
        $app->redirect('/');
    } else {
        // send message to the user that the message failed to sent
        // log out there was an error
        $app->redirect('/contact');
    }
});

$app->run();
?>