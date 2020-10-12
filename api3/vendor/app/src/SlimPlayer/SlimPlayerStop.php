<?php

namespace SlimPlayer;

class SlimPlayerStop
{
    public static function stopPlayerNow()
    {
        //Parar Player
        if(!file_exists('player_stop.lock')) {
            touch('player_stop.lock');
        }

        //Forcar Parada
        if(!file_exists('player_stop.lock')) {

            $fo = fopen("player_stop.lock", "w+");

            if (fwrite($fo, 1)) {
                return true;
            } else {
                return false;
            }

            fclose($fo);
        }

        return true;

    }

}
