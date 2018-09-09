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
            'person' => [
                'uri' => '/person', // returns 4,5,6
                'expectedText' => [
                    'Person', // title
                    'Placa de retea D-Link DFE-528TX',
                    'Placa de retea D-Link DFE-530TX',
                    'Sursa ATX Delux 500W',
                ]
            ],
            'camera' => [
                'uri' => '/camera', // returns 7,8,9
                'expectedText' => [
                    'Camera', // title
                    'Placa de retea TRENDnet, 10/100/1000M',
                    'Placa de retea TRENDnet, 10/100MBPS, Chipset Realtek',
                    'Back UPS CS 650 VA',
                ]
            ],
        ];
    }
}
