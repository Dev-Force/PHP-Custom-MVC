<?php 

namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Rename {

    public function rename($args) {
        return $this->prepareStatement($args, 'rename');
    }

}