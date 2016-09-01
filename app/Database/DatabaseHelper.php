<?php

namespace App\Database;

class DatabaseHelper {

    use Traits\QueryBuilder;
    use Traits\Generic;

    use Traits\toRef;
    use Traits\BindParam;
    use Traits\Execute;

    // Optional
    use Traits\Insert;
    use Traits\Select;
    use Traits\Delete;
    use Traits\Create;
    use Traits\Rename;
    use Traits\Update;
    

    private $options = [
        "host"          => "localhost",
        "username"      => "root",
        "password"      => "",
        "database"      => "database",
    ];

    private $con;

    /**
     * @param $array (host, username, password, database_name)
     */
    public function __construct($array = []) {
        foreach ( $array as $key => $value ) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $array[$key];
            }
        }
        $this->connect();
    }

    public function __destruct() {
        $this->disconnect();
    }

    private function connect() {
        //Create the connection
        $this->con = new \mysqli(
            $this->options['host'],
            $this->options['username'],
            $this->options['password'],
            $this->options['database']
        );
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        } 
    }

    private function disconnect() {
        mysqli_close($this->con);
    }

    public function getResultsAssoc() {
        $results = [];
        $result = $this->stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }

        return $results;
    }

    public function getAffectedRows() {
        return $this->con->affected_rows;
    }
    
}