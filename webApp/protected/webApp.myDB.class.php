<?php

class myDB
{
    protected static $_instance;

    public function __construct($server = null, $user = null, $pass = null, $db = null)
    {
        if ($server == null)
            $server = DB_SERVER;

        if ($user == null)
            $user = DB_USER;

        if ($pass == null)
            $pass = DB_PASS;

        if ($db == null)
            $db = DB_DB;

        self::$_instance = new mysqli($server, $user, $pass, $db);

        if (self::$_instance->connect_error)
            die("Connection failed: " . self::$_instance->connect_error);

        self::$_instance->set_charset("utf8");

    }

    private function checkAndReconnect()
    {
        if (!self::$_instance->ping())
            $this->__construct();

    }

    public function query($sql, $params = null)
    {
        if ($params == null)
            $params = array();

        $this->checkAndReconnect();

        $sql = self::putParams($sql, $params);

        $res = self::$_instance->query($sql);

        if (self::$_instance->errno)
            die("Query failed: " . self::$_instance->error . "<br/>SQL command: " . $sql);

        return $res;

    }

    public function queryRow($sql, $params = null)
    {
        return mysqli_fetch_object($this->query($sql, $params));
    }

    public function getRow($rows)
    {
        return mysqli_fetch_object($rows);
    }

    public function insert($table, $params, $ignore = false)
    {
        $this->checkAndReconnect();

        $fields     = array_keys($params);
        $fieldsDots = ':' . implode(', :', $fields);
        $fieldNames = implode(', ', $fields);

        $this->query("INSERT " . ($ignore ? "IGNORE " : "") . "INTO $table ($fieldNames) VALUES ($fieldsDots)", $params);

        return self::$_instance->affected_rows;
    }

    public function update($table, $updateParams, $whereParams = null)
    {
        $this->checkAndReconnect();

        $params = array();

        $sqlUpdate = '';

        foreach ($updateParams as $k => $v) {

            $sqlUpdate .= ', ' . $k . ' = :' . $k;

            $params[$k] = $v;

        }

        $sqlUpdate = substr($sqlUpdate, 2);

        $sqlWhere = '';

        if ($whereParams != null) {

            foreach ($whereParams as $k => $v) {

                $sqlWhere .= ' AND ' . $k . ' = :' . $k;

                $params[$k] = $v;

            }

            $sqlWhere = ' WHERE ' . substr($sqlWhere, 5);

        }

        $sql = 'UPDATE ' . $table . ' SET ' . $sqlUpdate . $sqlWhere;

        return $this->query($sql, $params);

    }

    public function close()
    {
        self::$_instance->close();
    }

    private function putParams($sql, $params)
    {
        $params = self::escapeParams($params);

        $sql = $this->putParamsWildcard($sql, $params, "%", "%");

        $sql = $this->putParamsWildcard($sql, $params, "%");

        $sql = $this->putParamsWildcard($sql, $params, "", "%");

        $sql = $this->putParamsWildcard($sql, $params, "IN(", ")", true);

        $sql = $this->putParamsWildcard($sql, $params);

        return $sql;

    }

    private function putParamsWildcard($sql, $params, $startWildcard = "", $endWildcard = "", $removeQuotes = false)
    {
        $quote = $removeQuotes ? '' : '"';

        $keys = array_keys($params);

        $vals = array_values($params);

        for ($n=0; $n<count($vals); $n++)
            $vals[$n] = $quote . $startWildcard . $vals[$n] . $endWildcard . $quote;

        $startWildcard .= ':';

        for ($n = 0; $n < count($keys); $n++)
            $sql = preg_replace('/' . preg_quote($startWildcard) . '\b' . preg_quote($keys[$n]) . '\b'  . preg_quote($endWildcard) . '/', $vals[$n], $sql);

        return $sql;

    }

    private function escapeParams($params)
    {
        $this->checkAndReconnect();

        foreach ($params as $k => $v)
            $params[$k] = self::$_instance->real_escape_string($v);

        return $params;

    }

    public function insertId()
    {
        return self::$_instance->insert_id;
    }

    public function beginTransaction()
    {
        self::$_instance->autocommit(false);
    }

    public function commit()
    {
        self::$_instance->commit();
        self::$_instance->autocommit(true);
    }

    public function numRows($r)
    {
        return mysqli_num_rows($r);
    }

}