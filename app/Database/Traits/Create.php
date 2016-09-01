<?php 

namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Create {

    public function create($args) {
        return $this->prepareStatement($args, 'create');
    }

}