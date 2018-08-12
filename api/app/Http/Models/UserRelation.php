<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Redis;
use App\Http\Constants\FriendStatus;
use App\Http\Constants\SubscribeStatus;

class UserRelation extends Model {
    private $collection = "user_relations";
    private $expiry = 86400;
    private $limit = 3000000;

    public function __contruct() {
    }

    public function getAllFriends($userId) {
        return $this->execute("SELECT * FROM " . $this->collection . " WHERE user_id_1=$userId AND friend_status = " . FriendStatus::FRIEND . " ALLOW FILTERING",
            array('page_size' => $this->limit));
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

    public function insert($userId1, $userId2, $friendStatus, $subscribeStatus) {
        Redis::del("user_relation:$userId1:$userId2");
        $this->execute("INSERT INTO " . $this->collection . " (user_id_1, user_id_2, friend_status, subscribe_status, created_ts, updated_ts)
                        VALUES (
                        $userId1, 
                        $userId2, 
                        $friendStatus, 
                        $subscribeStatus, 
                        toTimestamp(now()), 
                        toTimestamp(now())
                        )");
    }

    public function updateFriendStatus($userId1, $userId2, $friendStatus) {
        Redis::del("user_relation:$userId1:$userId2");
        $this->execute("UPDATE " . $this->collection . " SET
                        friend_status=$friendStatus,
                        updated_ts=toTimestamp(now())
                    WHERE
                        user_id_1=$userId1 AND
                        user_id_2=$userId2");
    }

    public function updateSubscribeStatus($userId1, $userId2, $subscribeStatus) {
        Redis::del("user_relation:$userId1:$userId2");
        $this->execute("UPDATE " . $this->collection . " SET
                        subscribe_status=$subscribeStatus,
                        updated_ts=toTimestamp(now())
                    WHERE
                        user_id_1=$userId1 AND
                        user_id_2=$userId2");
    }

}