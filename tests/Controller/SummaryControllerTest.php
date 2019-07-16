<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * @brief      Functional tests of the SummaryController class.
 *
 */
class SummaryControllerTest extends WebTestCase
{
    /**
     * @brief      Test the index view of the system. 
     *
     * @details    A welcome page and some useful info.
     *
     */
    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testUser',
            'PHP_AUTH_PW'   => 'testPass',
        ]);
        $crawler = $client->request('GET', '/en/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $today = (new \DateTime())->format('Y-m-d');
        $this->assertSelectorTextContains('html h1.section_title', 'Welcome to Liturgia Cheznous');
        $this->assertSelectorTextContains('html .dateTitle', $today);
    }
}
