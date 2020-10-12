<?php

//Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;
use \SlimPlayer\SlimPlayerRandomNumber;
use \SlimPlayer\SlimPlayerSendRandomNumber;

$app = new Slim();

$app->get('/player_running/:player_id', function($player_id) {

    //Gerar numero randomico para enviar para a API3
    $n = new SlimPlayerRandomNumber();
    $api1_value = $n->getRandomNumber();

    //file_put_contents('log', date("F j, Y, g:i a")." api1_value:".$api1_value."\r\n", FILE_APPEND);

    //Gerar numero randomico para enviar para a API3
    $s = new SlimPlayerSendRandomNumber();
    $s->sendRandomNumber('api1_val', 'api3', $api1_value, $player_id);

    exit;
});

$app->get('/controll/player-reset', function(){

    file_put_contents('log', "");

    exit;
});

//$app->post();

//$app->put();

//$app->delete();

$app->run();

?>