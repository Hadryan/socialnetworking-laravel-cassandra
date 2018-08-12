<?php

namespace App\Http\Models;

class Feed extends Model {
    private $collection = "feeds";

    public function __contruct() {
    }

    public function insert($senderId, $message, $mentionedIds = []) {
        $mentionedIds = "[".implode(",", $mentionedIds)."]";
        $this->execute("INSERT INTO " . $this->collection . " (id, sender_id, message, mentioned_ids, created_ts, updated_ts)
                        VALUES (
                        now(), 
                        $senderId, 
                        '$message', 
                        $mentionedIds,
                        toTimestamp(now()), 
                        toTimestamp(now())
                        )");
    }

}