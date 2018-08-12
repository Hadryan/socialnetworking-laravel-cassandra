<?php

namespace App\Http\Controllers;

use App\Http\Models\UserRelation;
use App\Http\Models\User;
use App\Http\Models\Feed;
use App\Http\Constants\StatusCode;
use Mockery\Exception;
use Illuminate\Http\Request;

class FeedController extends Controller {

    private $status;
    private $payload;

    public function __construct() {
        $this->status = StatusCode::SUCCESS;
        $this->payload = null;
    }

    public function post(Request $request, UserRelation $userRelationModel, User $userModel, Feed $feedModel) {
        $sender = $request->json("sender", null);
        $text = $request->json("text", null);

        if (empty($sender) || $text === null) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            try {
                $user = $userModel->getByEmail($sender);
                if ($user == null) {
                    $this->status = StatusCode::BAD_REQUEST;
                    $this->payload['error'] = $sender . " has not been registered";
                    $valid = false;
                } else {
                    $senderUUID = $user['id']['uuid'];
                }

                // get all of the subscribers
                if ($valid) {
                    $this->payload['recipients'] = [];
                    $subscribers = $userRelationModel->getAllSubscribers($senderUUID);
                    foreach ($subscribers as $s) {
                        $sData = $userModel->getById($s['user_id_1']->uuid());
                        if ($sData != null)
                            $this->payload['recipients'][] = $sData['email'];
                    }

                    $mentionedIds = [];
                    $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
                    preg_match_all($pattern, $text, $matches);
                    foreach ($matches[0] as $m) {
                        $mData = $userModel->getByEmail($m);
                        if ($mData != null) {
                            if (!in_array($mData['email'], $mentionedIds))
                                $mentionedIds[] = $mData['id']['uuid'];
                            if (!in_array($mData['email'], $this->payload['recipients']))
                                $this->payload['recipients'][] = $mData['email'];
                        }
                    }
                    $feedModel->insert($senderUUID, $text, $mentionedIds);
                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }
}