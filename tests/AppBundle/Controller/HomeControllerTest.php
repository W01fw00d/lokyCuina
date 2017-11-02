<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase {
    public function testIndex() {
        $client = static::createClient();

        $client->followRedirects();
        $crawler = $client->request('GET', '/');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Home page - Welcome!")')->count()
        );
    }
}
