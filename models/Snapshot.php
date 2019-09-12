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

    public function __construct() {
        parent::__construct();
    }

    public function getTable() {
        return self::table;
    }

    public function save() {
        $a = [];
        foreach ($this->fields as $field) {
            $a[$field] = $this->{$field};
        }
        if (!$this->id) {
            return self::$db->insert($this->getTable(), $a);
        }
        return self::$db->update($this->getTable(), $a, sprintf("id=%d", $this->id));
    }
}