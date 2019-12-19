<?php
namespace App\Tests\Controller\Api;

use App\Tests\ApiTester;

class SecurityControllerCest
{
    private $token = null;

    public function testSecurityPostLogin(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendPOST('/api/login');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    public function testSecurityPostRegister(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $user = [
            'name' => 'Name 1',
            'username' => 'name1',
            'email' => 'name1@example.com',
            'password' => 'name1',
            'twitterid' => 'name1',
        ];

        $I->sendPOST('/api/register', $user);

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
