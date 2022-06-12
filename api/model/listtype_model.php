<?php
class listtype_model {
    public static $template;

    public static function init() {
        core::loadClass('database');
        self::$template = array(
                'type_id'        => '0',
                'type_abbr'        => '',
                'type_name'     => '',
                'type_title'    => '',
                'type_short'    => '');
    }

    /**
     * Return a list of all word types in the database
     */
    public static function listAll($tags = false) {
        $query = "SELECT * FROM sm_listtype WHERE type_istag =? ORDER BY type_name;";
        if(!$res = database::retrieve($query, [$tags ? '1' : '0'])) {
            return false;
        }

        $ret = array();
        while($row = database::get_row($res)) {
            $ret[] = self::fromRow($row);
        }
        return $ret;
    }

    /**
     * Get details of a word type using its abbreviation
     *
     * @param string $type_short
     */
    public static function getByShort($type_short) {
        $query = "SELECT * FROM sm_listtype WHERE type_short =?";

        if($row = database::get_row(database::retrieve($query, [$type_short]))) {
            return self::fromRow($row);
        }
        return false;
    }

    /**
     * Get details of a word type using its ID
     *
     * @param int $type_id
     */
    public static function get($type_id) {
        $query = "SELECT * FROM sm_listtype WHERE type_id =?";
        if(!$row = database::get_row(database::retrieve($query, [$type_id]))) {
            return false;
        }
        return self::fromRow($row);
    }

    private static function fromRow($row, $depth = 0) {
        return database::row_from_template($row, self::$template);
    }
}


