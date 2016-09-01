<?php 

namespace App\Database\Traits;

// Uses BindParam, QueryBuilder, Execute
// Used by Database Helper

trait Generic {

    /**
     * [prepareStatement description]
     * @param  array $args      
     * @param  string $type     type can be one of the following: create, select, insert, delete
     * @return boolean          execution result
     */
    protected function prepareStatement($args, $type) {
        $args['type'] = $type;

        if(!$this->stmt = $this->con->prepare(
                $this->buildQuery($args)
            )
        ) {
            die ("Mysql Error: " . htmlspecialchars($this->con->error));
            return false;
        }

        if (array_key_exists('var_types', $args)) {
            $this->bindParam($args);
        }

        return $this->execute();

    }

}