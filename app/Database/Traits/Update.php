<?php 
namespace App\Database\Traits;

// Uses Generic
// Used by DatabaseHelper

trait Update {

    public function update($args) {
        return $this->prepareStatement($args, 'update');
    }

}