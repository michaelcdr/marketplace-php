<?php

namespace infra;

abstract class MySqlRepository
{

    protected $conn;

    public function __construct($conn)
    {
        // echo "chegou em MySqlRepository<br>";
        $this->conn = $conn;
    }
}
