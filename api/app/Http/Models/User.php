<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Redis;

class User extends Model {
    private $collection = "users";
    private $expiry = 86400;


    /**
     * Get user record by email
     * @param $email
     * @return Cassandra\Rows object
     */
    public function getByEmail($email) {
        $cacheName = 'user:email:' . $email;
        $result = Redis::get($cacheName);
        if ($result == null) {
            $cqlResult = $this->execute("SELECT * FROM " . $this->collection . " WHERE email='$email'");
            if ($cqlResult != null && $cqlResult->count() > 0) {
                $result = json_encode($cqlResult[0]);
                Redis::set($cacheName, $result, 'EX', $this->expiry);
                $result = json_decode($result, true);
            }
        } else {
            $result = json_decode($result, true);
        }

        return $result;
    }

    /**
     * Get user record by ID
     * @param $id
     * @return Cassandra\Rows object
     */
    public function getById($id) {
        $cacheName = 'user:id:' . $id;
        $result = Redis::get($cacheName);
        if ($result == null) {
            $cqlResult = $this->execute("SELECT * FROM " . $this->collection . " WHERE id=$id");
            if ($cqlResult != null && $cqlResult->count() > 0) {
                $result = json_encode($cqlResult[0]);
                Redis::set($cacheName, $result, 'EX', $this->expiry);
                $result = json_decode($result, true);
            }
        } else {
            $result = json_decode($result, true);
        }

        return $result;
    }


    /**
     * Insert new user
     * @param $email
     */
    public function insert($email) {
        Redis::del("user:email:$email");
        $this->execute("INSERT INTO " . $this->collection . " (id, email, created_ts)
                        VALUES (now(), '$email', toTimestamp(now()))");
    }
}