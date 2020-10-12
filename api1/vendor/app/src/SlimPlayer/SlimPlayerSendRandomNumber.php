<?php

namespace SlimPlayer;

class SlimPlayerSendRandomNumber
{
    public function sendRandomNumber($from_api, $target_api, $value, $player_id)
    {
        #curl
        $url  = "http://localhost/webdev/testes/slim-player-demo/{$target_api}/player_running/{$from_api}/{$value}/{$player_id}";
        $init = curl_init($url);

        //file_put_contents('log', date("F j, Y, g:i a")." ".$url."\r\n", FILE_APPEND);

        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($init, CURLOPT_SSL_VERIFYPEER, 0);
        /*curl_setopt($init, CURLOPT_POST, 1);
        curl_setopt($init, CURLOPT_POSTFIELDS, [$this->numberPlayer => $this->numberPlayer]);*/
        curl_setopt($init, CURLOPT_URL, $url);

        $getResponse = curl_exec($init);

        curl_close($init);

        //file_put_contents('log', date("F j, Y, g:i a")." CURL getResponse: ".$getResponse."\r\n", FILE_APPEND);

        return $getResponse;

    }

}
?>