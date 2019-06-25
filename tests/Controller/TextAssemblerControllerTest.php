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
     *             Then it tests is making the redirections acoording to
     *             the form.
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/assembler/');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $today = (new \DateTime())->format('Y-m-d');
        $crawler = $client->submitForm('Get Text');
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
        $this->assertTrue(
            $client->getResponse()->isRedirect('/assembler/text/pdf/CNBB/'.$today.'/')
        );
    }
}
