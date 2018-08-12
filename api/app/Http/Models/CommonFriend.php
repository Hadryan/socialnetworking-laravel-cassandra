<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Redis;
use App\Http\Constants\ConnectionType;

class CommonFriend {

    private function getCacheName($userId1, $userId2) {
        return "cf;" . (($userId1 > $userId2) ? "$userId2:$userId1" : "$userId1:$userId2");
    }

    public function getAll($userId1, $userId2) {
        $cacheName = $this->getCacheName($userId1, $userId2);
        return Redis::lrange($cacheName, 0, -1);
    }

    public function generate($userId1, $userId2) {
        $data = [];
        $data['friends'] = [$userId1, $userId2];
        $data['type'] = ConnectionType::FRIEND;
        \Amqp::publish(env('CF_ROUTINGKEY', 'common_friends'), json_encode($data) , ['queue' => env('CF_QUEUE', 'cf_act')]);
    }

}