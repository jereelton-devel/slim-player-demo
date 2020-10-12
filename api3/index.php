<?php

//Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;
use \SlimPlayer\SlimPlayerRandomNumber;
use \SlimPlayer\SlimPlayerQuery;
use \SlimPlayer\SlimPlayerSendPostAPI;
use \SlimPlayer\SlimPlayerStart;
use \SlimPlayer\SlimPlayerStop;
use \SlimPlayer\SlimPlayerStatus;
use \SlimPlayer\SlimPlayerCheckWinner;

$app = new Slim();

$app->get('/controll/player-start', function(){

    //Verificar Status do player, remover lock
    $player_start = SlimPlayerStart::startPlayerNow();
    if(strstr($player_start, 'ERRO:')) {
        echo $player_start;
        exit;
    }

    //Mensagem de inicio com sucesso
    //echo $player_start;

    //file_put_contents('log', date("F j, Y, g:i a")." ".$player_start."\r\n", FILE_APPEND);

    //Gerar numero randomico para o desafio dos players
    $n = new SlimPlayerRandomNumber();
    $api3_value = $n->getRandomNumber();

    //file_put_contents('log', date("F j, Y, g:i a")." api3_value: ".$api3_value."\r\n", FILE_APPEND);

    //Gravar o numero gerado na base de dados
    $stmt = new SlimPlayerQuery();
    $stmt->queryInsertRandomNumber($api3_value,'api3_val', 1);
    if(!$stmt->queryExecute()) {
        echo "Erro: API3 NÃO CONSEGUIU GRAVAR O VALOR";
        touch('player_stop.lock');
        exit;
    }

    //file_put_contents('log', date("F j, Y, g:i a")." queryInsertRandomNumber\r\n", FILE_APPEND);

    //Ultimo id gerado para controle geral do player
    $stmt->queryGetLastId();
    $i = $stmt->queryResult();
    if(count($i) == 0) {
        echo "Erro: API3 NÃO CONSEGUIU OBTER O ULTIMO ID GERADO";
        touch('player_stop.lock');
        exit;
    }

    //file_put_contents('log', date("F j, Y, g:i a")." id:".$i[0]['id']."\r\n", FILE_APPEND);

    //Avisar API1 que o player iniciou
    $p = new SlimPlayerSendPostAPI();
    $p->postInformation('api1', $i[0]['id']);

    exit;
});

$app->get('/controll/player-stop', function(){

    if(SlimPlayerStop::stopPlayerNow() === false) {

        echo 'ERRO: SLIM PLAYER NÃO CONSEGUIU PARAR O PLAYER';

        //file_put_contents('log', date("F j, Y, g:i a")." ERRO: SLIM PLAYER NÃO CONSEGUIU PARAR O PLAYER\r\n", FILE_APPEND);

    } else {

        $stmt = new SlimPlayerQuery();

        //Ultimo id gerado para controle geral do player
        $stmt->queryGetLastId();
        $i = $stmt->queryResult();
        if(count($i) == 0) {
            echo "Erro: API3 NÃO CONSEGUIU OBTER O ULTIMO ID GERADO NO STOP";
            touch('player_stop.lock');
            exit;
        }

        //file_put_contents('log', date("F j, Y, g:i a")." lastID: ".$i[0]['id']."\r\n", FILE_APPEND);

        $stmt->queryUpdatePlayerStatus($i[0]['id'],0);

        echo 'FINISHED';
    }

    exit;
});

$app->get('/controll/player-reset', function(){

    //Gravar o numero gerado na base de dados
    $stmt = new SlimPlayerQuery();
    $stmt->queryPlayerReset();
    if(!$stmt->queryExecute()) {

        //file_put_contents('log', date("F j, Y, g:i a")." ERRO: API3 NÃO CONSEGUIU RESETAR O PLAYER\r\n", FILE_APPEND);

        echo "ERRO: API3 NÃO CONSEGUIU RESETAR O PLAYER";
        touch('player_stop.lock');
        exit;
    } else {

        file_put_contents('log', "");

        echo "PLAYER RESETADO COM SUCESSO";
    }

    exit;
});

$app->get('/info/:action', function($action){

    if($action == 'player-result') {

        $stmt = new SlimPlayerQuery();

        //Ultimo id gerado para controle geral do player
        $stmt->queryGetLastId();
        $i = $stmt->queryResult();
        if(count($i) == 0) {
            echo "Erro: API3 NÃO CONSEGUIU OBTER O ULTIMO ID GERADO PARA MOSTRAR O RESULTADO";
            touch('player_stop.lock');
            exit;
        }

        //file_put_contents('log', date("F j, Y, g:i a")." id: ".$i[0]['id']."\r\n", FILE_APPEND);

        $stmt->queryWinnerSelect($i[0]['id']);
        $winner = $stmt->queryResult();

        //file_put_contents('log', date("F j, Y, g:i a")." select: ".json_encode($winner)."\r\n", FILE_APPEND);

        echo json_encode([
            'id' => $winner[0]['id'],
            'api3_val' => $winner[0]['api3_val'],
            'api1_val' => $winner[0]['api1_val'],
            'api2_val' => $winner[0]['api2_val'],
            'winner' => $winner[0]['winner']
        ]);

        exit;
    }
});

$app->get('/info/player-status', function(){

    if(SlimPlayerStatus::checkPlayerStatus() == true) {
        echo 1;
    } else {
        echo 0;
    }

    //file_put_contents('log', date("F j, Y, g:i a")." checkPlayerStatus: ".SlimPlayerStatus::checkPlayerStatus()."\r\n", FILE_APPEND);

    exit;
});

$app->get('/player_running/:from_api/:value/:player_id', function($from_api, $value, $player_id){

    //Verificar status do player
    if(SlimPlayerStatus::checkPlayerStatus() == true) {

        $stmt = new SlimPlayerQuery();

        //Gravar o valor das apis na base de dados
        $stmt->queryUpdateRandomNumber($value, $from_api, $player_id, 1);
        if(!$stmt->queryExecute()) {
            echo "Erro: API3 NÃO CONSEGUIU ATUALIZAR O PLAYER DA API {$from_api}";
            touch('player_stop.lock');
            exit;
        }

        if ($from_api == 'api1_val') {

            //Avisar API2 que a API1 jogou
            $p = new SlimPlayerSendPostAPI();
            $p->postInformation('api2', $player_id);

            //file_put_contents('log', date("F j, Y, g:i a")." from api1: ".$from_api."\r\n", FILE_APPEND);

        }

        if ($from_api == 'api2_val') {

            //Verificar ganhador do desafio
            $stmt->queryWinnerSelect($player_id);
            $winner = $stmt->queryResult();

            //file_put_contents('log', date("F j, Y, g:i a")." from api2: ".$from_api."\r\n", FILE_APPEND);

            $checkWinner = SlimPlayerCheckWinner::getPlayerWinner($winner[0]['api3_val'], $winner[0]['api1_val'], $winner[0]['api2_val']);

            //file_put_contents('log', date("F j, Y, g:i a")." checkWinner: ".$checkWinner."\r\n", FILE_APPEND);

            //Atualizar a base de dados com as informacoes sobre o ganhador do player
            $stmt->queryUpdateRandomNumber($checkWinner, 'winner', $player_id, 1);
            if(!$stmt->queryExecute()) {
                echo "Erro: API3 NÃO CONSEGUIU ATUALIZAR O VENCEDOR DO PLAYER ID {$player_id}";
                touch('player_stop.lock');
                exit;
            }

            /*Continuar Player*/
            sleep(3);

            //file_put_contents('log', date("F j, Y, g:i a")." Continuar Player\r\n", FILE_APPEND);

            //Gerar numero randomico para o desafio dos players
            $n = new SlimPlayerRandomNumber();
            $api3_value = $n->getRandomNumber();

            //Gravar o numero gerado na base de dados
            //$stmt = new SlimPlayerQuery(); //Instanciado no inicio da rota
            $stmt->queryInsertRandomNumber($api3_value,'api3_val', 1);
            if(!$stmt->queryExecute()) {
                echo "Erro: API3 NÃO CONSEGUIU CONTINUAR O PLAYER";
                touch('player_stop.lock');
                exit;
            }

            //Ultimo id gerado para controle geral do player
            $stmt->queryGetLastId();
            $i = $stmt->queryResult();
            if(count($i) == 0) {
                echo "Erro: API3 NÃO CONSEGUIU OBTER O ULTIMO ID GERADO PARA CONTINUAR";
                touch('player_stop.lock');
                exit;
            }

            //Avisar API1 que o player iniciou
            $p = new SlimPlayerSendPostAPI();
            $p->postInformation('api1', $i[0]['id']);

        }

    } else {

        file_put_contents('log', date("F j, Y, g:i a")." API3 NÃO ESTA RODANDO...\r\n", FILE_APPEND);

    }

});

//$app->post();

//$app->put();

//$app->delete();

$app->run();

?>