<?php

namespace App\Http\Controllers;

use App\Http\Models\UserRelation;
use App\Http\Models\User;
use App\Http\Constants\StatusCode;
use App\Http\Constants\FriendStatus;
use App\Http\Constants\SubscribeStatus;
use Mockery\Exception;
use Illuminate\Http\Request;

class SubscribeController extends Controller {

    private $status;
    private $payload;

    public function __construct() {
        $this->status = StatusCode::SUCCESS;
        $this->payload = null;
    }

    public function connect(Request $request, UserRelation $userRelationModel, User $userModel) {
        $requestor = $request->json("requestor", null);
        $target = $request->json("target", null);

        if (empty($requestor) || empty($target)) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            try {

                $requestorUser = $userModel->getByEmail($requestor);
                if ($requestorUser == null) {
                    $this->status = StatusCode::BAD_REQUEST;
                    $this->payload['error'] = $requestor . " has not been registered";
                    $valid = false;
                } else {
                    $requestorUUID = $requestorUser['id']['uuid'];
                }

                if ($valid) {
                    $targetUser = $userModel->getByEmail($target);
                    if ($targetUser == null) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $target . " has not been registered";
                        $valid = false;
                    } else {
                        $targetUUID = $targetUser['id']['uuid'];
                    }
                }

                // check if connection has not been made, then make connection (both ways)
                if ($valid) {
                    $relation = $userRelationModel->getRelation($requestorUUID, $targetUUID);
                    if ($relation != null && $relation['subscribe_status']['value'] == SubscribeStatus::SUBSCRIBE) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $requestor . " has been subscribed to " . $target. ".";
                    } else {
                        if ($relation == null) {
                            $userRelationModel->insert($requestorUUID, $targetUUID, FriendStatus::NOT_FRIEND, SubscribeStatus::SUBSCRIBE);
                        } else if ($relation['subscribe_status']['value'] != SubscribeStatus::SUBSCRIBE) {
                            $userRelationModel->updateSubscribeStatus($requestorUUID, $targetUUID, SubscribeStatus::SUBSCRIBE);
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }

    public function block(Request $request, UserRelation $userRelationModel, User $userModel) {
        $requestor = $request->json("requestor", null);
        $target = $request->json("target", null);

        if (empty($requestor) || empty($target)) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            try {
                $requestorUser = $userModel->getByEmail($requestor);
                if ($requestorUser == null) {
                    $this->status = StatusCode::BAD_REQUEST;
                    $this->payload['error'] = $requestor . " has not been registered";
                    $valid = false;
                } else {
                    $requestorUUID = $requestorUser['id']['uuid'];
                }

                if ($valid) {
                    $targetUser = $userModel->getByEmail($target);
                    if ($targetUser == null) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $target . " has not been registered";
                        $valid = false;
                    } else {
                        $targetUUID = $targetUser['id']['uuid'];
                    }
                }

                // check if connection has not been made, then make connection (both ways)
                if ($valid) {
                    $relation = $userRelationModel->getRelation($requestorUUID, $targetUUID);
                    if ($relation != null && $relation['subscribe_status']['value'] == SubscribeStatus::BLOCK) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $requestor . " has blocked " . $target. ".";
                    } else {
                        if ($relation == null) {
                            $userRelationModel->insert($requestorUUID, $targetUUID, FriendStatus::NOT_FRIEND, SubscribeStatus::BLOCK);
                        } else if ($relation['subscribe_status']['value'] != SubscribeStatus::BLOCK) {
                            $userRelationModel->updateSubscribeStatus($requestorUUID, $targetUUID, SubscribeStatus::BLOCK);
                        }
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