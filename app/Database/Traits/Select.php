<?php 

namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Select {

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
     *       'var_types' => [
     *           's',
     *           's',
     *           's',
     *       ],
     *
     *       'where' => [
     *           ['dept_no', '=', 'd001'],
     *       ],
     *
     *       'order' => [
     *           'column',
     *           'DESC',
     *       ],
     *  ]
     * 
     * @param  array $args  An array filled with all the required elements
     * @return boolean      True if the select was successful, false if it wasnt
     */
    public function select($args) {
        return $this->prepareStatement($args, 'select');
    }

}