<?php

namespace SlimPlayer;

class SlimPlayerStatus
{
    public static function checkPlayerStatus()
    {
        if(file_exists('player_stop.lock')) {
            return false;
        } else {
            return true;
        }

    }

}
