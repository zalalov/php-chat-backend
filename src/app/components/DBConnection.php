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

        try {
            $this->sqlite = new SQLite3(BASE_DIR . DIRECTORY_SEPARATOR . $config["db"]["path"]);
        } catch (Exception $e) {
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
    public static function escape($var) {
        return SQLite3::escapeString($var);
    }

    /**
     * Sanitize string/array
     * @param $str_arr
     * @return string
     */
    public static function sanitize($str_arr) {
        if (is_array($str_arr)) {
            $data = '';

            foreach ($str_arr as $key => $val) {
                $data[$key] = self::escape($val);
            }

            return $data;
        }

        return self::escape($str_arr);
    }

    /**
     * Make query
     * @param $query
     * @return SQLite3Result
     * @throws Exception
     */
    private function _query($query) {
        $res = $this->sqlite->query($query);

        if (!$res) {
            throw new Exception($this->sqlite->lastErrorMsg());
        }

        return $res;
    }

    /**
     * Truncate table
     * @param $tableName
     */
    public function truncate($tableName) {
        $this->_query("DELETE FROM ". $tableName);
        $this->_query("DELETE FROM sqlite_sequence WHERE name = '". $tableName ."'");
    }

    /**
     * Get last inserted row's id
     * @return int
     */
    public function sqlite_insert_id() {
        return $this->sqlite->lastInsertRowID();
    }

    /**
     * Insert row
     * @param $tableName
     * @param array $inData
     * @return bool|SQLite3Result
     */
    public function insert($tableName, $inData = []) {
        if (!empty($inData) && !empty($tableName)) {
            $cols = $vals = '';

            foreach($inData as $key => $val) {
                $cols .= $key .", ";
                $vals .= "'". $val ."', ";
            }

            $query = "INSERT INTO ". $tableName ."(". trim($cols, ', ') .") VALUES(". trim($vals, ', ') .") ";

            return $this->_query($query);
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
    public function update($tableName, $inData = [], $condition) {
        if (!empty($inData) && !empty($tableName) && !empty($condition)) {
            $str = '';

            foreach ($inData as $key => $val) {
                $str .= $key ." = '". $val ."', ";
            }

            $query = "UPDATE ". $tableName ." SET ". trim($str, ', ') ." WHERE ". $condition;

            return $this->_query($query);
        } else {
            return false;
        }
    }

    /**
     * Execute query and fetch one record
     * @param $query
     * @return array
     */
    private function _rowArray($query) {
        $res = $this->_query($query);
        $row = $res->fetchArray($this->mode);

        return $row;
    }

    /**
     * Execute query and fetch multiple records
     * @param $query
     * @return array
     */
    private function _rowsArray($query) {
        $rows = [];

        if ($res = $this->_query($query)) {
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
    public function fetchRow($table_name, $condition = "1", $column = '*') {
        $query = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->_rowArray($query);

        return $row;
    }

    /**
     * Fetch records
     * @param $table_name
     * @param string $condition
     * @param string $column
     * @return mixed
     */
    public function fetchRows($table_name, $condition = "1", $column = '*') {
        $query = "SELECT ". $column ." FROM ". $table_name ." WHERE ". $condition;
        $row = $this->_rowsArray($query);

        error_log($query);

        return $row;
    }
}