<?php

namespace App\Tests\Controller\Api;

use App\DataFixtures\LoadTestUserData;
use App\Entity\User;
use App\Tests\FunctionalTester;
use Firebase\JWT\JWT;

class TokensControllerCest
{
    public function testGetNewToken(FunctionalTester $I)
    {
        // $I->truncateDatabases(['users']);

        $I->haveHttpHeader('Authorization', 'Basic ' . base64_encode('testuser:password'));
        $I->sendAjaxPostRequest('/api/tokens/new');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $response = json_decode($I->getLastResponseContent(), true);

        $token = $response['token'];

        $aux = explode('.', $token);

        $header = JWT::jsonDecode(JWT::urlsafeB64Decode($aux[0]));
        $I->assertEquals(
            $header->typ,
            'JWT',
            'Header does not include the correct type'
        );

        $decoded = JWT::jsonDecode(JWT::urlsafeB64Decode($aux[1]));
        $I->assertEquals(
            $decoded->username,
            'testuser',
            'Payload does not include the correct username'
        );
    }

    public function _before(FunctionalTester $I)
    {
        // $I->loadFixtures(LoadTestUserData::class);

        $users = $I->grabEntitiesFromRepository(User::class, []);
        // var_dump($users);
    }
}
