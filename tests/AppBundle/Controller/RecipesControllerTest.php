<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipesControllerTest extends WebTestCase {
    private function startOnRecipesPage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

       $link = $crawler
            ->filter('a:contains("Recipes")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertGreaterThan(
            0,
            $crawler->filter('h1:contains("Recipes")')->count()
        );

        return array(
            'client' => $client,
            'crawler' => $crawler
        );
    }

    public function testIndex() {
        $testUtils = $this->startOnRecipesPage();
    }
}
