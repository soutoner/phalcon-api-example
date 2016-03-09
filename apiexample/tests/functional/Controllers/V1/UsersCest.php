<?php

namespace Controllers\V1;

use Helper\EndpointTest;
use \FunctionalTester;

class UsersCest extends EndpointTest
{
    public function __construct()
    {
        parent::__construct(__FILE__);
    }

    public function indexReturnsUsers(FunctionalTester $I)
    {
        $I->sendGet($this->endpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $items = count(json_decode($I->grabResponse()));
        $I->assertGreaterThan(0, $items);
    }
}