<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $response = $client->getResponse();
        $this->assertEquals(true, $response->isRedirection());
        $this->assertEquals(true, $response->isRedirect('/listing'));
    }
}
