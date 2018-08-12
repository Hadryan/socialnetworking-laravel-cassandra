<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Redis;

class CommonFriend {


    private function getCacheName($userId1, $userId2) {
        return "cf;" . (($userId1 > $userId2) ? "$userId2:$userId1" : "$userId1:$userId2");
    }

    public function add($userId1, $userId2, $friend) {
        $cacheName = $this->getCacheName($userId1, $userId2);
        Redis::lpush($cacheName, $friend);
    }

    public function truncate($userId1, $userId2) {
        $cacheName = $this->getCacheName($userId1, $userId2);
        Redis::del($cacheName);
    }

    public function getAll($userId1, $userId2) {
        $cacheName = $this->getCacheName($userId1, $userId2);
        return Redis::lrange($cacheName, 0, -1);
    }

    public function delete($userId1, $userId2, $friend) {
        $cacheName = $this->getCacheName($userId1, $userId2);
        Redis::lrem($cacheName, 0, $friend);
    }
}