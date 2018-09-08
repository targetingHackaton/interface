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
                    'Listing', // title
                ]
            ],
            'all' => [
                'uri' => '/all', // returns 1,2,3
                'expectedText' => [
                    'All', // title
                    '1',
                    '2',
                    '3',
                ]
            ],
            'personal' => [
                'uri' => '/personal', // returns 4,5,6
                'expectedText' => [
                    'Personal', // title
                    '4',
                    '5',
                    '6',
                ]
            ],
            'camera' => [
                'uri' => '/camera', // returns 7,8,9
                'expectedText' => [
                    'Camera', // title
                    '7',
                    '8',
                    '9',
                ]
            ],
        ];
    }
}
