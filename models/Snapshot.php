<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 9/12/2019
 * Time: 14:46
 */

namespace models;

use lib\Model;

class Snapshot extends Model {

    const table = "snapshot";

    protected $fields = ["mobile", "longitude", "latitude", "created_at"];

    protected $pk = "id";

    public function __construct() {
        parent::__construct();
    }

    public function tableName() {
        return self::table;
    }

    /*** @return string */
    public function getMobile() {
        return $this->data['mobile'];
    }
    /*** @return double */
    public function getLongitude() {
        return $this->data['longitude'];
    }
    /*** @return double */
    public function getLatitude() {
        return $this->data['latitude'];
    }
    /*** @return string */
    public function getCreatedTime() {
        return $this->data['created_at'];
    }
    public function getId() {
        return $this->data['id'];
    }
    public function setMobile($mobile) {
        $this->data['mobile'] = $mobile;
    }
    public function setLongitude(/* double */ $longitude) {
        $this->data['longitude'] = $longitude;
    }
    public function setLaitude(/* double */$lagitude) {
        $this->data['latitude'] = $lagitude;
    }
    public function setCreatedTime(/* string */$createdAt) {
        if (!$createdAt) {
            $createdAt = date("Y-m-d H:i:s", time());
        }
        $this->data['created_at'] = $createdAt;
    }

    public function save() {
        $a = [];
        foreach ($this->fields as $field) {
            $a[$field] = $this->{$field};
        }
        if (!$this->id) {
            return self::$db->insert($this->tableName(), $a);
        }
        return self::$db->update($this->tableName(), $a, sprintf("id=%d", $this->id));
    }

    public static function all($begin, $offset, $where = []) {
        $o = new self();
        $f = $o->fields;
        array_unshift($f, $o->pk);
        $limit = sprintf("%d,%d", $begin, $offset);
        $a = self::$db->select($o->tableName(), $where, $f, 'id desc', $limit);
        // echo '<pre>'; var_dump($a); die;
        return array_map(function($e) use ($f) {
            $o = new Snapshot();
            array_walk($f, function($name) use (&$o, $e) {
                $o->data[$name] = $e[$name];
            });
            return $o;
        }, $a);
    }

    /**
     * $where string
     * @param string $where
     * @return mixed
     */
    public static function count($where = "") {
        $sql = "SELECT count(id) as cnt FROM ".self::table;
        if (isset($where[0])) {
            $sql .= " WHERE ".$where;
        }
        $a = self::getDB()->query($sql, array(), \PDO::FETCH_OBJ);
        return intval($a[0]->cnt);
    }

    public function __toString() {
        return json_encode($this->data);
    }

}