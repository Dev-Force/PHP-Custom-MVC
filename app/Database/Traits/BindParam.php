<?php 

namespace App\Database\Traits;

Trait BindParam {

    protected function bindParam($args) {
        $bindString = "";

        // Create Bind String 
        if (array_key_exists('var_types', $args)) {
            foreach ($args['var_types'] as $value) {
                $bindString .= $value;
            }
        }

        // Create Array with bind String and Values to use it with bind_param
        $refArr = [
            $bindString
        ];

        if (array_key_exists('values', $args)) {
            foreach($args['values'] as $value) {
                $refArr[] = $value;
            }    
        }

        if (array_key_exists('where', $args)) {
            foreach ($args['where'] as $value) {
                if (array_key_exists('subquery', $value)) {
                    $key = $value['subquery'];
                    while (array_key_exists('subquery', $key)) {
                        $key = $key['subquery'];
                    }

                    foreach($key['where'] as $value) {
                        $refArr[] = $value[2];    
                    }
                    
                } else {
                    $refArr[] = $value[2];    
                }
            }
        }

        // Turn Values to References, to use them in Recletion Class below
        // (bind_params requires references php 5.3+ )
        $refArr = $this->refValues($refArr);

        //Bind Parameters using Reflection Class
        $ref = new \ReflectionClass('mysqli_stmt');
        $method = $ref->getMethod("bind_param");
        $method->invokeArgs($this->stmt, $refArr);
    }

}