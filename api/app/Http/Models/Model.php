<?php

namespace App\Http\Models;

use Cassandra;

class Model {
    protected $session;

    public function __construct() {
        $cluster = Cassandra::cluster()
            ->withContactPoints(config('database.connections.cassandra.host'))
            ->build();
        $this->session = $cluster->connect(config('database.connections.cassandra.database'));
    }

    public function execute($query, $options = []) {
        return $this->session->execute($query, $options);
    }

}