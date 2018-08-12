<?php

namespace App\Http\Controllers;


use App\Http\Constants\ConnectionType;
use App\Http\Constants\StatusCode;
use App\Http\Models\User;
use App\Http\Models\UserRelation;
use App\Http\Models\CommonFriend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    private $status;
    private $payload;
    public function __construct() {
        $this->status = StatusCode::SUCCESS;
        $this->payload = null;
    }

    public function common(Request $request, User $userModel, UserRelation $userRelationModel, CommonFriend $cfModel) {
        $friends = $request->json("friends", null);
        $type = $request->json("type", null);

        if ($friends == null || empty($friends) || count($friends) != 2 || !in_array($type, [ConnectionType::FRIEND, ConnectionType::UNFRIEND])) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            // check if emails have been registered
            $valid = true;
            $friendsUser = [];
            try {
                foreach ($friends as $idx => $email) {
                    $user = $userModel->getByEmail($email);
                    if ($user == null) {
                        $this->status = StatusCode::BAD_REQUEST;
                        $this->payload['error'] = $email . " has not been registered";
                        $valid = false;
                        break;
                    } else {
                        $friendsUser[$idx] = $user;
                    }
                }

                if ($valid) {
                    $allFriendsBase = $userRelationModel->getAllFriendOwners($friendsUser[1]['id']['uuid']);
                    foreach ($allFriendsBase as $f) {
                        $data = [];
                        $data['id'] = $friendsUser[1]['id']['uuid'];
                        $data['email'] = $friendsUser[1]['email'];
                        //$cfModel->delete($friendsUser[0]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));
                        if ($type == ConnectionType::FRIEND)
                            $cfModel->add($friendsUser[0]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));

                    }

                    $allFriendsBase = $userRelationModel->getAllFriendOwners($friendsUser[0]['id']['uuid']);
                    foreach ($allFriendsBase as $f) {
                        $data = [];
                        $data['id'] = $friendsUser[0]['id']['uuid'];
                        $data['email'] = $friendsUser[0]['email'];
                        //$cfModel->delete($friendsUser[1]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));
                        if ($type == ConnectionType::FRIEND)
                            $cfModel->add($friendsUser[1]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));

                    }

                    $this->payload['common_friends'] = $cfModel->getAll($friendsUser[0]['id']['uuid'], $friendsUser[0]['id']['uuid']);

                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }


        return $this->output($this->status, $this->payload);
    }
}