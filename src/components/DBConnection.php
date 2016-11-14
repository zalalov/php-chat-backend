<?php

class DbConnection {
    private $sqlite;
    private $mode;

    function __construct($mode = SQLITE3_ASSOC) {
        global $config;

        $this->mode = $mode;
        $this->sqlite = new SQLite3(BASE_DIR . DIRECTORY_SEPARATOR . $config["db"]["path"]);

        if ($this->sqlite === null) {
            throw new Exception("Cannot connect to database", 500);
        }
    }

    function __destruct() {
        @$this->sqlite->close();
    }

    function escape($var) {
        return $this->sqlite->escapeString($var);
    }

    function sanitize($str_arr) {
        if (is_array($str_arr)) {
            $data = '';

            foreach ($str_arr as $key => $val) {
                $data[$key] = $this->escape($val);
            }

            return $data;
        }

        return $this->escape($str_arr);
    }

    function query($query) {
        // prevent SQLi: Hello,  BUNQ code reviewer )
        $q = $this->sanitize($query);
        $res = $this->sqlite->query($q);

        if (!$res) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }

        return $res;
    }

    function truncate($tableName) {
        $this->query("DELETE FROM ". $tableName);
        $this->query("DELETE FROM sqlite_sequence WHERE name = '". $tableName ."'");
    }

    function sqlite_insert_id() {
        return $this->sqlite->lastInsertRowID();
    }

    function insert($tableName, $inData = []) {
        if (!empty($inData) && !empty($tableName)) {
            $cols = $vals = '';

            foreach($inData as $key => $val) {
                $cols .= $key .", ";
                $vals .= "'". $val ."', ";
            }

            $query = "INSERT INTO ". $tableName ."(". trim($cols, ', ') .") VALUES(". trim($vals, ', ') .") ";

            return $this->query($query);
        } else {
            return false;
        }
    }

    function update($tableName, $inData = [], $condition) {
        if (!empty($inData) && !empty($tableName) && !empty($condition)) {
            $str = '';

            foreach ($inData as $key => $val) {
                $str .= $key ." = '". $val ."', ";
            }

            $query = "UPDATE ". $tableName ." SET ". trim($str, ', ') ." WHERE ". $condition;

            return $this->query($query);
        } else {
            return false;
        }
    }

    function rowArray($query) {
        $res = $this->query($query);
        $row = $res->fetchArray($this->mode);

        return $row;
    }

    function fetchArray($query) {
        $rows = [];

        if ($res = $this->query($query)) {
            while ($row = $res->fetchArray($this->mode)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    function fetchRow($table_name, $condition = "1", $column = '*') {
        $qry = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->rowArray($qry);

        return $row;
    }

    function fetchRows($table_name, $condition = "1", $column = '*') {
        $qry = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->fetchArray($qry);

        return $row;
    }
}