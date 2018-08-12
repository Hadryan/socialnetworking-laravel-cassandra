<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Redis;
use App\Http\Constants\FriendStatus;
use App\Http\Constants\SubscribeStatus;

class UserRelation extends DBModel {
    private $collection = "user_relations";
    private $expiry = 86400;
    private $limit = 3000000;

    public function __contruct() {
    }

    public function getAllFriends($userId) {
        return $this->execute("SELECT * FROM " . $this->collection . " WHERE user_id_1=$userId AND friend_status = " . FriendStatus::FRIEND . " ALLOW FILTERING",
            array('page_size' => $this->limit));
    }

    public function getAllFriendOwners($userId) {
        return $this->execute("SELECT * FROM " . $this->collection . " WHERE user_id_2=$userId AND friend_status = " . FriendStatus::FRIEND . " ALLOW FILTERING",
            array('page_size' => $this->limit));
    }

    public function getTotalFriends($userId) {
        $result = 0;
        $cqlresult = $this->execute("SELECT count(user_id_1) as total FROM " . $this->collection . " WHERE user_id_1=$userId AND friend_status = " . FriendStatus::FRIEND . " ALLOW FILTERING",
            array('page_size' => $this->limit));
        if ($cqlresult != null && $cqlresult->count() > 0) {
            $result = $cqlresult[0]['total']->value();
        }
        return $result;
    }

    public function getAllSubscribers($userId) {
        return $this->execute("SELECT * FROM " . $this->collection . " WHERE user_id_2=$userId AND subscribe_status = " . SubscribeStatus::SUBSCRIBE . " ALLOW FILTERING",
            array('page_size' => $this->limit));
    }

    public function getRelation($userId1, $userId2) {
        $cacheName = "user_relation:$userId1:$userId2";
        $result = Redis::get($cacheName);
        if ($result == null) {
            $cqlResult = $this->execute("SELECT * FROM " . $this->collection . " WHERE user_id_1=$userId1 AND user_id_2=$userId2");
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

}