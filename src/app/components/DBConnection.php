<?php

class DbConnection {
    private $sqlite;
    private $mode;

    /**
     * DbConnection constructor.
     * @param int $mode
     * @throws Exception
     */
    function __construct($mode = SQLITE3_ASSOC) {
        global $config;

        $this->mode = $mode;
        $this->sqlite = new SQLite3(BASE_DIR . DIRECTORY_SEPARATOR . $config["db"]["path"]);

        if ($this->sqlite === null) {
            throw new Exception("Cannot connect to database", 500);
        }
    }

    /**
     * DbConnection destructor
     */
    function __destruct() {
        @$this->sqlite->close();
    }

    /**
     * Escape string
     * @param $var
     * @return string
     */
    function escape($var) {
        return $this->sqlite->escapeString($var);
    }

    /**
     * Sanitize string/array
     * @param $str_arr
     * @return string
     */
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

    /**
     * Make query
     * @param $query
     * @return SQLite3Result
     * @throws Exception
     */
    function query($query) {
        // prevent SQLi: Hello,  BUNQ code reviewer )
        $q = $this->sanitize($query);
        $res = $this->sqlite->query($q);

        if (!$res) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }

        return $res;
    }

    /**
     * Truncate table
     * @param $tableName
     */
    function truncate($tableName) {
        $this->query("DELETE FROM ". $tableName);
        $this->query("DELETE FROM sqlite_sequence WHERE name = '". $tableName ."'");
    }

    /**
     * Get last inserted row's id
     * @return int
     */
    function sqlite_insert_id() {
        return $this->sqlite->lastInsertRowID();
    }

    /**
     * Insert row
     * @param $tableName
     * @param array $inData
     * @return bool|SQLite3Result
     */
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

    /**
     * Update row
     * @param $tableName
     * @param array $inData
     * @param $condition
     * @return bool|SQLite3Result
     */
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

    /**
     * Execute query and fetch one record
     * @param $query
     * @return array
     */
    function rowArray($query) {
        $res = $this->query($query);
        $row = $res->fetchArray($this->mode);

        return $row;
    }

    /**
     * Execute query and fetch multiple records
     * @param $query
     * @return array
     */
    function rowsArray($query) {
        $rows = [];

        if ($res = $this->query($query)) {
            while ($row = $res->fetchArray($this->mode)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Fetch one record
     * @param $table_name
     * @param string $condition
     * @param string $column
     * @return array
     */
    function fetchRow($table_name, $condition = "1", $column = '*') {
        $qry = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->rowArray($qry);

        return $row;
    }

    /**
     * Fetch records
     * @param $table_name
     * @param string $condition
     * @param string $column
     * @return mixed
     */
    function fetchRows($table_name, $condition = "1", $column = '*') {
        $qry = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->rowsArray($qry);

        return $row;
    }
}