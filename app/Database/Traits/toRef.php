<?php  

namespace App\Database\Traits;

trait toRef {

    protected function refValues($arr){
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }

}