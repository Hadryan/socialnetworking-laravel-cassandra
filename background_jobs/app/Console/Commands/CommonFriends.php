<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Constants\ConnectionType;
use App\Http\Models\User;
use App\Http\Models\UserRelation;
use App\Http\Models\CommonFriend;

class CommonFriends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:common-friends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deamon to generate common friend list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *$message->body
     * @return mixed
     */
    public function handle(User $userModel, UserRelation $userRelationModel, CommonFriend $cfModel)
    {
        \Log::debug("Start Rabbit consumer..");
        echo "Start Rabbit consumer..\n";
        while (true) {
            \Amqp::consume(env('CF_QUEUE', 'cf_act'), function ($message, $resolver) use ($userModel, $userRelationModel, $cfModel) {
                try {
                    \Log::debug("Get " . $message->body);

                    echo "Get " . $message->body . "\n";
                    $body = json_decode($message->body, true);
                    if (array_key_exists('friends', $body) && array_key_exists('type', $body)) {
                        \Log::debug("Process " . $message->body);

                        echo "Process " . $message->body . "\n";
                        $result = $this->generate($body['friends'], $body['type'], $userModel, $userRelationModel, $cfModel);
                        if ($result) {
                            $resolver->acknowledge($message, ['persistent' => true]);
                            \Log::debug("Ack " . $message->body);
                            echo "Ack " . $message->body . "\n";
                        } else {
                            \Log::debug("Reject " . $message->body);
                            echo "Reject " . $message->body . "\n";
                        }
                    } else {
                        $resolver->acknowledge($message, ['persistent' => true]);
                        \Log::debug("Ack invalid message " . $message->body);
                        echo "Ack invalid message " . $message->body . "\n";
                    }
                } catch (\Exception $ex) {
                    \Log::error($ex->getMessage());
                    echo $ex->getMessage() . "\n";
                }
            });
            sleep(1);
        }
        \Log::debug("End consuming");
        echo "End consuming\n";
    }

    public function generate($friends, $type, $userModel, $userRelationModel, $cfModel) {
        $result = true;
        if ($friends == null || empty($friends) || count($friends) != 2 || !in_array($type, [ConnectionType::FRIEND, ConnectionType::UNFRIEND])) {
            $result = false;
        } else {
            // check if emails have been registered
            $friendsUser = [];
            try {
                foreach ($friends as $idx => $id) {
                    $user = $userModel->getById($id);
                    if ($user == null) {
                        $result = false;
                        break;
                    } else {
                        $friendsUser[$idx] = $user;
                    }
                }

                if ($result) {
                    $allFriendsBase = $userRelationModel->getAllFriendOwners($friendsUser[1]['id']['uuid']);
                    foreach ($allFriendsBase as $f) {
                        $data = [];
                        $data['id'] = $friendsUser[1]['id']['uuid'];
                        $data['email'] = $friendsUser[1]['email'];
                        $cfModel->delete($friendsUser[0]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));
                        if ($type == ConnectionType::FRIEND)
                            $cfModel->add($friendsUser[0]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));

                    }

                    $allFriendsBase = $userRelationModel->getAllFriendOwners($friendsUser[0]['id']['uuid']);
                    foreach ($allFriendsBase as $f) {
                        $data = [];
                        $data['id'] = $friendsUser[0]['id']['uuid'];
                        $data['email'] = $friendsUser[0]['email'];
                        $cfModel->delete($friendsUser[1]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));
                        if ($type == ConnectionType::FRIEND)
                            $cfModel->add($friendsUser[1]['id']['uuid'], $f['user_id_1']->uuid(), json_encode($data));

                    }
                }
            } catch (Exception $ex) {
                $result = false;
            }
        }

        return $result;
    }
}
