<?php

namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Insert {

    /**
     * Insert row to Database
     *
     * Example argument:
     * 
     *  [
     *       'table' => 'table_name',
     *
     *       'params' => [
     *           'p1',
     *           'p2',
     *           'p3',
     *       ],
     *
     *       'values' => [
     *           'v1',
     *           'v2',
     *           'v3',
     *       ],
     *
     *       'var_types' => [
     *           's',
     *           's',
     *           's',
     *       ]
     *
     *  ]
     * 
     * @param  array $args  An array filled with all the required elements
     * @return boolean      True if the insert was successful, false if it wasnt
     */
    public function insert($args) {
        return $this->prepareStatement($args, 'insert');
    }

}