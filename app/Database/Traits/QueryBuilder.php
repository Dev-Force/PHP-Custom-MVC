<?php 
    
namespace App\Database\Traits;

// Used by Generic

trait QueryBuilder {

    /**
     * Build SQL Query based on its type
     * 
     * @param  array $args  An array filled with all the required elements
     * @return string       Empty String on failure, Query String on success
     */
    protected function buildQuery($args) {
        // if table does not exist or args is not array return false
        if (!is_array($args) || !array_key_exists('table', $args)) {
            return "";
        }

        $string = "";

        if ($args['type'] === 'insert') {
            // if params, values and append are all missing return false
            if (!array_key_exists('params', $args) && !array_key_exists('values', $args) && !array_key_exists('append', $args)) {
                return "";
            }

            $string .= "INSERT INTO ";
            $string .= $args['table'];
            
            if (isset($args['params']) && !empty($args['params'])) {
                $string .- " (";

                foreach ($args['params'] as $key => $value) {
                    $string .= ($key === 0 ? $value  : ", " . $value );
                }

                $string .= ")";
            }

            if(isset($args['values']) && !empty($args['values'])) {
                $string .= " VALUES (";

                foreach ($args['values'] as $key => $value) {
                    $string .= ($key === 0 ? "?" : ", ?");
                }

                $string .= ")";
            }

        } else if ($args['type'] === 'delete') {
            $string .= "DELETE FROM " . $args['table'] . " ";

        } else if ($args['type'] === 'update') {
            $string .= "UPDATE " . $args['table'];

            // Add all params to Update query
            if (isset($args['params']) && !empty($args['params']) && isset($args['values']) && !empty($args['values'])) {
                $string .= " SET "; 
                for ($i=0; $i<count($args['params']); $i++) {
                    if(!$i === 0)
                        $string .= " ,";    
                    $string .= $args['params'][$i] . "= ?";
                }
            }

        } else if ($args['type'] === 'select') {
            $string .= "SELECT ";

            // Add all params to Select query
            if (isset($args['params']) && !empty($args['params'])) {
                foreach($args['params'] as $key => $value) {
                    $string .= ($key === 0 ? $value  : ", " . $value );
                }
            }

            $string .= " FROM " . $args['table'];

        } else if ($args['type'] === 'create') { 
            $string .= "CREATE TABLE " . $args['table'];
            
            if( (array_key_exists('column_names', $args) && !array_key_exists('column_types', $args)) 
                || (!array_key_exists('column_names', $args) && array_key_exists('column_types', $args)) ) {
                echo "i am in ";
                return "";
            }

            if (array_key_exists('column_names', $args) && array_key_exists('column_types', $args)) {
                $string .= " (";
                
                for ($i=0; $i<count($args['column_names']); $i++) {
                    if(!$i === 0)
                        $string .= " ,";    
                    $string .= $args['column_names'][$i] . " " . $args['column_types'][$i];
                }

                $string .= ")";
            }

        } else if ($args['type'] === 'rename') {
            if(!array_key_exists('table', $args) ||  !array_key_exists('name', $args)) {
                return "";
            }

            $string .= "RENAME TABLE " . $args['table'] . " TO " . $args['name'];
        }


        //Check for Where and subqueries
        if (isset($args['where']) && is_array($args['where']) && !empty($args['where'])) {
            foreach ($args['where'] as $key => $value) {
                if ($key === 0) {
                    $string .= " WHERE ";
                } else {
                    $string .= " AND ";  
                }
                //check for subquery
                if (array_key_exists('subquery', $value)) {
                    $string .= $value[0] . " " . $value[1] . " (" . $this->buildQuery($value['subquery']) . ") ";
                } else {
                    $string .= $value[0] . " " . $value[1] . " ?";
                }
            } 
        }

        if(array_key_exists('append', $args)) {
            $string .= " " . $args['append'];
        }

        // Check for Order By parameter
        if (!empty($args['order']) && is_array($args['where']) && !empty($args['where'])) {
            $string .= " ORDER BY " . $args['order'][0] . " " . $args['order'][1];
        }

        // Check for Limit parameter
        if (!empty($args['limit'])) {
            $string .= " LIMIT " . $args['limit'];
        }

        // echo $string;

        return $string;

    }

}