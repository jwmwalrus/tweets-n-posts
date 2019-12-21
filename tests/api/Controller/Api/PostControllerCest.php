<?php namespace App\Tests\Controller\Api;
use App\Entity\Post;
use App\Entity\User;
use App\Tests\ApiTester;

class PostControllerCest
{
    private $token = null;

    public function testPostNew(ApiTester $I)
    {
        $testUser = $I->grabEntityFromRepository(
            User::class,
            ['username' => 'testuser']
        );

        $post = [
            'title' => 'Title 1',
            'content' => 'Some content',
            'user_id' => $testUser->getId(),
        ];

        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendPOST('/api/posts/new', $post);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);

        $response = json_decode($I->grabResponse(), true);

        $I->assertArrayHasKey(
            'id',
            $response,
            'Response does not contain id'
        );

        $I->assertTrue(
            intval($response['id']) > 0,
            'Response id <= 0'
        );
    }

    public function testPostNewInvalidUser(ApiTester $I)
    {
        $user = [
            'title' => 'Title 1',
            'content' => 'Some content',
            'user_id' => 32768,
        ];

        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendPOST('/api/posts/new', $user);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
    }

    public function testPostEdit(ApiTester $I)
    {
        $testUser = $I->grabEntityFromRepository(
            User::class,
            ['username' => 'testuser']
        );

        $oldPost = $I->grabEntityFromRepository(
            Post::class,
            ['author' => $testUser]
        );
        $id = $oldPost->getId();

        $post = [
            'title' => 'New title',
            'content' => 'Some new content',
        ];

        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendPOST("/api/posts/edit/{$id}", $post);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    public function testPostEditNotFound(ApiTester $I)
    {
        $post = [
            'title' => 'New title',
            'content' => 'Some new content',
        ];

        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendPOST('/api/posts/edit/32769', $post);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
    }

    public function _before(ApiTester $I)
    {
        exec('bin/console doctrine:fixtures:load -n > /dev/null 2>&1');

        if (empty($this->token)) {
            $I->haveHttpHeader('Authorization', 'Basic ' . base64_encode('testuser:password'));
            $I->sendPOST('/api/tokens/new');
            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
            $response = json_decode($I->grabResponse(), true);

            $this->token = $response['token'];
        }
    }
}
