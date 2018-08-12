<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use App\Http\Constants\StatusCode;
use Mockery\Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $status;
    private $payload;

    public function __construct() {
        $this->status = StatusCode::SUCCESS;
        $this->payload = null;
    }

    public function register(Request $request, User $userModel)
    {
        $email = $request->json("email", null);

        if ($email == null) {
            $this->status = StatusCode::BAD_REQUEST;
            $this->payload['error'] = "Bad request";
        } else {
            try {
                $user = $userModel->getByEmail($email);
                if ($user == null) {
                    $userModel->insert($email);
                } else {
                    $this->status = StatusCode::BAD_REQUEST;
                    $this->payload['error'] = "Email has been registered";
                }
            } catch (Exception $ex) {
                $this->status = StatusCode::INTERNAL_ERROR;
                $this->payload['error'] = $ex->getMessage();
            }
        }

        return $this->output($this->status, $this->payload);
    }
}
