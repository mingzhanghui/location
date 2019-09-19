<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 9/12/2019
 * Time: 14:42
 */

namespace lib;

abstract class Model {
    /**
     * @var Database
     */
    protected static $db = null;

    /**
     * @var array index
     */
    protected $fields = [];

    /**
     * @var array assoc
     */
    protected $data = [];

    /**
     * @var string primary key
     */
    protected $pk = "id";

    public function __construct() {
        if (is_null(self::$db)) {
            self::$db = new Database(DB_TYPE, DB_HOST,
                DB_NAME, DB_USER, DB_PASS);
        }
    }

    public static function getDB() {
        if (!self::$db) {
            self::$db = new Database(DB_TYPE, DB_HOST,
                DB_NAME, DB_USER, DB_PASS);
        }
        return self::$db;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return "";
        // throw new \RuntimeException("Attribute ".$name." does not exist.");
    }

    abstract function save();

    abstract function count($where = "");
}