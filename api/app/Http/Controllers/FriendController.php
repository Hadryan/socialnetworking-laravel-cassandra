<?php

namespace App\Http\Controllers;

use App\Http\Models\UserRelation;
use App\Http\Models\User;
use App\Http\Models\CommonFriend;
use App\Http\Constants\StatusCode;
use App\Http\Constants\FriendStatus;
use App\Http\Constants\SubscribeStatus;
use Mockery\Exception;
use Illuminate\Http\Request;

class FriendController extends Controller {

    private $status;
    private $payload;

    public function __construct() {
        $this->status = StatusCode::SUCCESS;
        $this->payload = null;
    }

    public function connect(Request $request, UserRelation $userRelationModel, User $userModel, CommonFriend $cfModel) {
        $friends = $request->json("friends", null);

        if ($friends == null || empty($friends) || count($friends) != 2) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            $friendsUUID = [];
            try {
                foreach ($friends as $idx => $email) {
                    $user = $userModel->getByEmail($email);
                    if ($user == null) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $email . " has not been registered";
                        $valid = false;
                        break;
                    } else {
                        $friendsUUID[$idx] = $user['id']['uuid'];
                    }
                }

                // check if connection has not been made, then make connection (both ways)
                if ($valid) {
                    $relation1 = $userRelationModel->getRelation($friendsUUID[0], $friendsUUID[1]);
                    $relation2 = $userRelationModel->getRelation($friendsUUID[1], $friendsUUID[0]);
                    if ($relation1 != null && $relation1['friend_status']['value'] == FriendStatus::FRIEND && $relation2 != null && $relation2['friend_status']['value'] == FriendStatus::FRIEND) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $friends[0] . " and " . $friends[1] . " have become friends.";
                    } else if (($relation1 != null && $relation1['subscribe_status']['value'] == SubscribeStatus::BLOCK) || ($relation2 != null && $relation2['subscribe_status']['value'] == SubscribeStatus::BLOCK)) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $friends[0] . " and " . $friends[1] . " can not be friend.";
                    } else {
                        if ($relation1 == null) {
                            $userRelationModel->insert($friendsUUID[0], $friendsUUID[1], FriendStatus::FRIEND, SubscribeStatus::SUBSCRIBE);
                        } else if ($relation1['friend_status']['value'] != FriendStatus::FRIEND && $relation1['subscribe_status']['value'] != SubscribeStatus::BLOCK) {
                            $userRelationModel->updateFriendStatus($friendsUUID[0], $friendsUUID[1], FriendStatus::FRIEND);
                        }
                        if ($relation2 == null) {
                            $userRelationModel->insert($friendsUUID[1], $friendsUUID[0], FriendStatus::FRIEND, SubscribeStatus::SUBSCRIBE);
                        }else if ($relation2['friend_status']['value'] != FriendStatus::FRIEND && $relation2['subscribe_status']['value'] != SubscribeStatus::BLOCK) {
                            $userRelationModel->updateFriendStatus($friendsUUID[1], $friendsUUID[0], FriendStatus::FRIEND);
                        }

                        $cfModel->generate($friendsUUID[1], $friendsUUID[0]);
                    }
                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }

    public function common(Request $request, User $userModel, CommonFriend $cfModel) {
        $friends = $request->json("friends", null);

        if ($friends == null || empty($friends) || count($friends) != 2) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            $friendsUUID = [];
            try {
                foreach ($friends as $idx => $email) {
                    $user = $userModel->getByEmail($email);
                    if ($user == null) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $email . " has not been registered";
                        $valid = false;
                        break;
                    } else {
                        $friendsUUID[$idx] = $user['id']['uuid'];
                    }
                }

                if ($valid) {
                    $this->payload['friends'] = [];
                    $cf = $cfModel->getAll($friendsUUID[0], $friendsUUID[1]);
                    if ($cf != null) {
                        foreach ($cf as $f) {
                            $fData = json_decode($f, true);
                        $this->payload['friends'][] = $fData['email'];
                        }
                    }
                    $this->payload['count'] = count($this->payload['friends']);
                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }

    public function list(Request $request, UserRelation $userRelationModel, User $userModel) {
        $email = $request->json("email", null);

        if ($email == null) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            try {
                // get ID
                $user = $userModel->getByEmail($email);
                if ($user == null) {
                    $this->status = StatusCode::BAD_REQUEST;
                    $this->payload['error'] = $email . " has not been registered";
                } else {
                    $this->payload['friends'] = [];
                    $this->payload['count'] = 0;
                    $friends = $userRelationModel->getAllFriends($user['id']['uuid']);
                    foreach ($friends as $f) {
                        $fData = $userModel->getById($f['user_id_2']->uuid());
                        if ($fData != null)
                            $this->payload['friends'][] = $fData['email'];

                        $this->payload['count']++;
                    }
                }

            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }
}