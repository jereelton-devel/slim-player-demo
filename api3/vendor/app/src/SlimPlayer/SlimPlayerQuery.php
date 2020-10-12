<?php

namespace SlimPlayer;

class SlimPlayerQuery
{   
    private $stmt;
    private $query;
    private $connect;
    private $tbname;

    public function queryPlayerReset() {
        $this->query = "TRUNCATE TABLE tb_slim_player;";
    }

    public function queryUpdatePlayerStatus($id, $status)
    {
        $this->query = "UPDATE
                            $this->tbname
                        SET
                            `player_status` = {$status}
                        WHERE
                            `id` = {$id}
                        LIMIT 1;";

        //file_put_contents('log', date("F j, Y, g:i a")." queryUpdatePlayerStatus: ".$this->query."\r\n", FILE_APPEND);

    }

    public function queryUpdateRandomNumber($value, $field, $id, $status)
    {
        $this->query = "UPDATE
                            $this->tbname
                        SET
                            `{$field}` = '{$value}', 
                            `player_status` = {$status}
                        WHERE
                            `id` = {$id}
                        LIMIT 1;";

        //file_put_contents('log', date("F j, Y, g:i a")." queryUpdateRandomNumber: ".$this->query."\r\n", FILE_APPEND);

    }

    public function queryGetLastId()
    {
        $this->query = "SELECT
                            id
                        FROM
                            tb_slim_player
                        ORDER BY
                            id DESC
                        LIMIT 1;";

        //file_put_contents('log', date("F j, Y, g:i a")." queryGetLastId: ".$this->query."\r\n", FILE_APPEND);

    }

    public function queryInsertRandomNumber($value, $field, $status)
    {
        $this->query = "INSERT
                        INTO
                            $this->tbname
                        SET
                            `{$field}` = {$value},
                            `player_status` = {$status};";

        //file_put_contents('log', date("F j, Y, g:i a")." queryInsertRandomNumber: ".$this->query."\r\n", FILE_APPEND);

    }
    
    public function queryWinnerSelect($id)
    {
        $this->query = "SELECT 
                            *
                        FROM
                            ".$this->tbname."
                        WHERE
                            `id` = {$id};";

        //file_put_contents('log', date("F j, Y, g:i a")." queryWinnerSelect: ".$this->query."\r\n", FILE_APPEND);

    }

    public function queryExecute()
    {
        try {

            $this->stmt = $this->connect->prepare($this->query);
            $r = $this->stmt->execute();

            return $r;

        } catch (\Exception $e) {

            file_put_contents('log', date("F j, Y, g:i a")." Exception: ".$e->getMessage()."\r\n", FILE_APPEND);
            return false;

        }
    }
    
    public function queryResult()
    {
        try {

            $this->stmt = $this->connect->prepare($this->query);
            $this->stmt->execute();

            return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {

            file_put_contents('log', date("F j, Y, g:i a")." Exception: ".$e->getMessage()."\r\n", FILE_APPEND);
            return false;
        }
    }
    
    public function __construct() {
        $this->connect = new SlimPlayerConnection();
        $this->connect = $this->connect->getConnection();
        $this->tbname = "tb_slim_player";
    }
    
}
?>