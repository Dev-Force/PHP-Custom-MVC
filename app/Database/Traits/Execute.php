<?php 

namespace App\Database\Traits;

trait Execute {

    function execute() {
        return mysqli_stmt_execute($this->stmt);
    }

}