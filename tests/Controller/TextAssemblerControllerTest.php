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
     *             Then it tests is making the redirections acording to
     *             the form.
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/en/assembler/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $today = (new \DateTime())->format('Y-m-d');
        $crawler = $client->submitForm('Get Text');
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
        $this->assertTrue(
            $client->getResponse()->isRedirect('/en/assembler/text/pdf/CNBB/'.$today.'/')
        );
    }

    /**
     * @brief      Test getText action.
     *
     * @details    First makes sure the route is restricted for pdf, rtf, 
     *             formats and the sources: CNBB and Igreja_Santa_Ines
     *
     */
    public function testGetText()
    {
        $client = static::createClient();
        $today = (new \DateTime())->format('Y-m-d');
        $client->request('GET', '/en/assembler/text/pdf/unknown/'.$today.'/');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $client->request('GET', '/en/assembler/text/algo/CNBB/'.$today.'/');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/en/assembler/text/pdf/Igreja_Santa_Ines/21000-12-10/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html div.warning', 'No text found.');

        $this->assertSelectorTextContains('html div.error_detail', 'Source : Igreja_Santa_Ines');

        $this->assertEquals(
            2,
            $crawler->filter('html div.error_detail')->count()
        );
        
        $this->assertEquals(
            1,
            $crawler->filter('html a:contains("Return")')->count()
        );
        $client->clickLink('Return');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/en/assembler/text/rtf/CNBB/1900-01-01/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html div.warning', 'No text found.');

        $this->assertSelectorTextContains('html div.error_detail', 'Source : CNBB');

        $this->assertEquals(
            2,
            $crawler->filter('html div.error_detail')->count()
        );
        
        $this->assertEquals(
            1,
            $crawler->filter('html a:contains("Return")')->count()
        );
        $client->clickLink('Return');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
