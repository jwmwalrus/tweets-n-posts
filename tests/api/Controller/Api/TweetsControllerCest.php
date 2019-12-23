<?php

namespace App\Tests\Controller\Api;

use App\Tests\ApiTester;

class TweetsControllerCest
{
    // tests
    public function testGetList(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $I->sendGET('/api/tweets/list');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $response = json_decode($I->grabResponse(), true);

        $I->assertIsArray(
            $response,
            'Response is not an array'
        );

        $I->assertTrue(
            count($response) > 1,
            'No tweets were updated'
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
