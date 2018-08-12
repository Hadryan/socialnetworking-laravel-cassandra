<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Constants\StatusCode;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/user', ['email' => 'aa@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'bb@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'cc@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'dd@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'ee@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'ff@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'gg@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'hh@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'ii@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'jj@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'kk@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'll@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'mm@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'nn@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'oo@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'pp@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'qq@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'rr@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'ss@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'tt@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'uu@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'vv@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'ww@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'xx@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'yy@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/user', ['email' => 'zz@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);


        $response = $this->json('POST', '/api/user', ['email' => 'aa@gmail.com']);
        $response->assertResponseStatus(StatusCode::BAD_REQUEST);
    }

    public function testMakeFriend()
    {
        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'bb@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'cc@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'dd@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'ee@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'ff@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);


        $response = $this->json('POST', '/api/friend', ['friends' => ['bb@gmail.com', 'cc@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);
        $response = $this->json('POST', '/api/friend', ['friends' => ['bb@gmail.com', 'zz@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS);



        $response = $this->json('POST', '/api/friend', ['friends' => ['aa@gmail.com', 'bb@gmail.com']]);
        $response->assertResponseStatus(StatusCode::BAD_REQUEST);
    }

    public function testFriendList()
    {
        $response = $this->json('POST', '/api/friend/list', ['email' => 'aa@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'friends',
                'count',
                'success'
            ])
            ->seeJson(['success' => true])
            ->seeJson(['count' => 5]);


        $response = $this->json('POST', '/api/friend/list', ['email' => 'bb@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'friends',
                'count',
                'success'
            ])
            ->seeJson(['success' => true])
            ->seeJson(['count' => 3]);

    }

    public function testCommonFriends()
    {
        $response = $this->json('POST', 'api/friend/common', ['friends' => ['aa@gmail.com', 'zz@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'friends',
                'success'
            ])
            ->seeJson(['success' => true]);


        $response = $this->json('POST', 'api/friend/common', ['friends' => ['aa@gmail.com', 'bb@gmail.com']]);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'friends',
                'success'
            ])
            ->seeJson(['success' => true]);

    }


    public function testSubscribe()
    {
        $response = $this->json('POST', '/api/subscribe', ['requestor' => 'zz@gmail.com', 'target' => 'aa@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);

        $response = $this->json('POST', '/api/subscribe', ['requestor' => 'zz@gmail.com', 'target' => 'aa@gmail.com']);
        $response->assertResponseStatus(StatusCode::BAD_REQUEST);
    }

    public function testBlock()
    {
        $response = $this->json('POST', '/api/block', ['requestor' => 'aa@gmail.com', 'target' => 'yy@gmail.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS);


        $response = $this->json('POST', '/api/block', ['requestor' => 'aa@gmail.com', 'target' => 'yy@gmail.com']);
        $response->assertResponseStatus(StatusCode::BAD_REQUEST);

    }

    public function testPostFeed()
    {
        $response = $this->json('POST', '/api/feed', ['sender' => 'aa@gmail.com', 'text' => 'Hi all! yy@gmail.com unknown@somewhere.com']);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'recipients',
                'success'
            ])
            ->seeJson(['success' => true]);


        $response = $this->json('POST', '/api/feed', ['sender' => 'oo@gmail.com', 'text' => 'So lonely~']);
        $response->assertResponseStatus(StatusCode::SUCCESS)
            ->seeJsonStructure([
                'recipients',
                'success'
            ])
            ->seeJson(['success' => true]);
    }

}
