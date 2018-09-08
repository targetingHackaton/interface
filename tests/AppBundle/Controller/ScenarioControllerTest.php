<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScenarioControllerTest extends WebTestCase
{
    /**
     * @dataProvider providerForRoutes
     * @param string $uri
     * @param array $expectedText
     */
    public function testRoutes(string $uri, array $expectedText)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $uri);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        foreach ($expectedText as $text) {
            $this->assertContains($text, $crawler->text());
        }
    }

    public function providerForRoutes(): array
    {
        return [
            'listing' => [
                'uri' => '/listing',
                'expectedText' => [
                    'Listing'
                ]
            ],
            'all' => [
                'uri' => '/all',
                'expectedText' => [
                    'All'
                ]
            ],
            'personal' => [
                'uri' => '/personal',
                'expectedText' => [
                    'Personal'
                ]
            ],
            'camera' => [
                'uri' => '/camera',
                'expectedText' => [
                    'Camera'
                ]
            ],
        ];
    }
}
