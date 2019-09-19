<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 9/12/2019
 * Time: 14:39
 */

namespace lib;

class Database extends \PDO {

    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        parent::__construct($DB_TYPE.':host='.$DB_HOST.'; dbname='.$DB_NAME, $DB_USER, $DB_PASS);
    }

    /**
     * @param string $sql  An SQL string
     * @param array $array  Parameters to bind
     * @param $fetchMode int A PDO Fetch Mode
     * @return mixed
     */
    public function query($sql, $array = array(), $fetchMode = \PDO::FETCH_ASSOC) {
        $sth= $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("{$key}", $value);
        }
        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
     * @param $table string
     * @param $where array [ ["name1","=","valuie1" ], ["name2", "like", "v%"] ]
     * @param $fields array ["name1", "name2"]
     * @param $order string "id desc"
     * @param $limit string "0,10"
     * @return array
     */
    public function select($table, $where, $fields, $order, $limit) {
        if (empty($where)) {
            $ws = "";
        } else {
            $wa = array_map(function($c) {
                return sprintf("`%s` %s '%s'", $c[0], $c[1], $c[2]);
            }, $where);
            $ws = "WHERE " . implode(" AND ", $wa);
        }
        if (empty($fields)) {
            $fs = "*";
        } else {
            $fs = "`".implode("`,`", $fields)."`";
        }
        if (empty($order)) {
            $os = "";
        } else {
            $os = "ORDER BY ".$order;
        }
        if (empty($limit)) {
            $ls = "";
        } else {
            $ls = "LIMIT ".$limit;
        }
        $sql = sprintf("SELECT %s FROM `%s` %s %s %s", $fs, $table, $ws, $os, $ls);
        // echo $sql; die;
        $sth = $this->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param array $data An associative array
     */
    public function insert($table, $data) {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table(`$fieldNames`) VALUES($fieldValues)");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param array $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($table, $data, $where) {
        ksort($data);

        // UPDATE table SET item1 = a, item2 = b, item3 = c WHERE something = 1;
        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key` = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * delete
     *
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer Affected Rows
     */
    public function delete($table, $where, $limit = 1) {
        return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    }

    // public function __destruct() {}

}