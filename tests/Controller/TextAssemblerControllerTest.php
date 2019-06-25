<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * @brief      Functional tests of the TextAssemblerController class.
 *
 * @details    Makes sure the text assembler works as expected.
 *
 */
class TextAssemblerControllerTest extends WebTestCase
{
    /**
     * @brief      Test the index view of the assembler
     *
     * @details    First makes sure the route responds as expected.
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/assembler/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
