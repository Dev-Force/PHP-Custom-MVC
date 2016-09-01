<?php 

namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Delete {

    public function delete($args) {
        return $this->prepareStatement($args, 'delete');
    }

}