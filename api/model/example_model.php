<?php
class example_model {
    public static $template;

    public static function init() {
        core::loadClass('database');

        self::$template = array(
                'example_id'                => '0',
                'example_str'                => '',
                'example_t_style'            => '',
                'example_k_style'            => '',
                'example_t_style_recorded'    => '0',
                'example_k_style_recorded'    => '',
                'example_en'                => '',
                'example_en_lit'            => '',
                'example_uploaded'            => '',
                'example_audio_tag'            => '');
    }

    /**
     * Get a single example using example_id
     *
     * @param number $id ID to fetch
     */
    public static function getById($example_id) {
        $sql = "SELECT * FROM sm_example WHERE example_id =?;";
        if($row = database::get_row(database::retrieve($sql, [(int)$example_id]))) {
            return database::row_from_template($row, self::$template);
        }
        return false;
    }

    /**
     * Get all examples associated with a given definition
     */
    public static function listByDef($def_id) {
        $query = "SELECT * FROM sm_examplerel " .
                "JOIN sm_example ON example_rel_example_id = example_id " .
                "WHERE example_rel_def_id =?";
        $ret = array();
        if($res = database::retrieve($query, [(int)$def_id])) {
            while($row = database::get_row($res)) {
                /* Load examples */
                $example = database::row_from_template($row, self::$template);
                $ret[] = $example;
            }
        }
        return $ret;
    }

    /**
     * Find examples which mention a given word. Use to prompt suggested additions to examples
     */
    public static function listByWordMention($spelling_t_style, $word_num) {
        $id = word_model::getIdStrBySpellingNum($spelling_t_style, $word_num);
        $query = "SELECT * FROM sm_example WHERE example_str LIKE ? or example_str LIKE ?;";
        $ret = array();
        if($res = database::retrieve($query, ['%[' . $id . '|%', '%[' . $id . ']%'])) {
            while($row = database::get_row($res)) {
                /* Load examples */
                $example = database::row_from_template($row, self::$template);
                $ret[] = $example;
            }
        }
        return $ret;
    }

    /**
     * Create a new example and return the ID
     */
    public static function insert($example_sm, $example_en) {
        $str = self::autobracket($example_sm);
        $query = "INSERT INTO sm_example (example_id, example_str, example_t_style, example_k_style, example_t_style_recorded, example_k_style_recorded, example_en, example_en_lit, example_uploaded, example_audio_tag) VALUES (NULL ,  ?,  ?,  ?,  ?,  ?, ?, ?, CURRENT_TIMESTAMP, ?);";
        $id = database::insert($query, [$str, $example_sm, '', '0', '0', $example_en, '', '']);
        return $id;
    }

    /* Wrap each word in single-brackets */
    private static function autobracket($str) {
        $a = explode(" ", $str);
        $i = 0;
        foreach($a as $b) {
            $a[$i] = "[".$a[$i]."]";
            $i++;
        }
        return join(" ", $a);
    }

    /**
     * @return number Total number of examples currently stored.
     */
    public static function countExamples() {
        $query = "SELECT COUNT(example_id) as example_count FROM sm_example;";
        if($row = database::get_row(database::retrieve($query))) {
            return (int)$row['example_count'];
        }
        return 0;
    }

    public static function update($example) {
        $query = "UPDATE sm_example SET example_str =?, example_en=? WHERE example_id =?";
        database::retrieve($query, [$example['example_str'], $example['example_en'], (int)$example['example_id']]);
    }

    public static function delete($example_id) {
        /* Delete an example, after removing it from everywhere it appears */
        $query = "DELETE FROM sm_exampleaudio WHERE example_id =?;";
        database::retrieve($query, [(int)$example_id]); // NB: this may leave orphan audio files.

        $query = "DELETE FROM sm_examplerel WHERE example_rel_example_id =?;";
        database::retrieve($query, [(int)$example_id]);

        $query = "DELETE FROM sm_example WHERE example_id =?;";
        database::retrieve($query, [(int)$example_id]);
        return true;
    }
}

