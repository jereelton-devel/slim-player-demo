<?php

namespace SlimPlayer;

class SlimPlayerStart
{
    public static function startPlayerNow()
    {
        if(file_exists('player_stop.lock')) {
            if(unlink('player_stop.lock')) {
                return "BEM VINDO AO SLIM PLAYER";
            } else {
                touch('player_stop.lock');
                return "ERRO: SLIM PLAYER NÃO PODE INICIAR";
            }
        } else {
            return "BEM VINDO AO SLIM PLAYER";
        }

    }

}
