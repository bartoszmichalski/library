<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Copyright
 */



class Connection
{
    private $mysqli;

    private $lastResult;

    /**
     * Connection constructor.
     *
     * @param $mysqli
     */
    public function __construct(mysqli $mysqli)
    {
        if ($mysqli->connect_error) {
            die('Nie udane połączenie. Błąd: ' . $mysqli->connect_error);
        }
        $this->mysqli = $mysqli;
    }

    public function query($sql)
    {
        $result = $this->mysqli->query($sql);
        if ($result == false) {
            die(sprintf("SQL: %s, Błąd: %s", $sql, $this->mysqli->error));
        }
        $this->lastResult = $result;
    }

    public function getLastResult()
    {
        return $this->lastResult;
    }
    public function getLastId() 
    {
        return $this->mysqli->insert_id;
    }
}


