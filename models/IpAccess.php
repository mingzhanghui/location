<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 2019-09-14
 * Time: 21:28
 */

namespace models;

use lib\Model;

class IpAccess extends Model {
    const table = "ip_access";

    protected $fields = ["ip", "mobile", "created_at"];

    protected $pk = "id";

    const MAX_ROW = 102400;  // 100k

    public function __construct() {
        parent::__construct();
    }

    public function tableName() {
        return self::table;
    }

    public function setIp($ip) {
        $this->data['ip'] = $ip;
    }

    public function setMobile($mobile) {
        $this->data['mobile']= $mobile;
    }

    //  alter table ip_access rename as ip_access_old;
    public function save() {
        if (!isset($this->data['created_at'])) {
            $this->data['created_at'] = date("Y-m-d H:i:s", time());
        }
        if (!$this->id) {
            $cnt = $this->count();
            if ($cnt > self::MAX_ROW) {
                $this->tableRotate();
                $this->newTable();
            }
            return self::getDB()->insert($this->tableName(), $this->data);
        }
        return self::getDB()->update($this->tableName(), $this->data, sprintf("id=%d", $this->id));
    }

    /**
     * MyISAM read count directly without where condition
     * @param string $where
     * @return int
     */
    public function count($where = "") {
        $sql = "SELECT count(id) as cnt FROM ".self::table;
        if (isset($where[0])) {
            $sql .= " WHERE ".$where;
        }
        $a = self::getDB()->query($sql, array(), \PDO::FETCH_OBJ);
        return intval($a[0]->cnt);
    }

    private function tableRotate() {
        $tbl = $this->tableName();
        $sql = sprintf("ALTER TABLE `%s` RENAME AS `%s_old`", $tbl, $tbl);
        self::getDB()->query($sql);

        $sql = sprintf("TRUNCATE `%s`", $tbl);
        self::getDB()->query($sql);

        $sql = sprintf("ATLER TABLE `%s` AUTO_INCREMENT=1", $tbl);
        self::getDB()->query($sql);
    }

    private function newTable() {
        $s = <<<EOF
CREATE TABLE `%s` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL COMMENT 'ip address xxx.xxx.xxx.xxx',
  `mobile` char(11) DEFAULT NULL COMMENT 'mobile number',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
EOF;
        $sql = sprintf($s, $this->tableName());
        return self::getDB()->query($sql);
    }
}