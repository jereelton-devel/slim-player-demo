<?php

namespace SlimPlayer;

class SlimPlayerCheckWinner
{
    public static function getPlayerWinner($v1, $v2, $v3)
    {
        $calc1 = $v1 - $v2;
        $calc2 = $v1 - $v3;

        if($calc1 < 0 && $calc2 < 0) {
            return 'nenhum';
        }

        if($calc1 >= 0 && $calc2 < 0) {
            return 'api1';
        }

        if($calc1 < 0 && $calc2 >= 0) {
            return 'api2';
        }

        if($calc1 == $calc2 && $calc1 >=0 && $calc2 >= 0) {
            return 'empate';
        }

        if($calc1 < $calc2) {
            return 'api1';
        } else {
            return 'api2';
        }
    }

}
