<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserTestFixtures;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @brief      Functional tests of the TextAssemblerController class.
 *
 * @details    Makes sure the text assembler works as expected.
 *
 */
class TextAssemblerControllerTest extends WebTestCase
{
    use FixturesTrait;
    
    private $client = null;

    public function setUp() : void
    {
        $this->loadFixtures([UserTestFixtures::class]);
        $this->client = static::createClient();
        $this->client->setServerParameters([]);

        //now login:
        $crawler = $this->client->request('GET', '/');
        $crawler=$this->client->followRedirect();
        $form = $crawler->selectButton('Entrar')->form(array(
            '_username'  => 'user@test.com',
            '_password'  => 'testPass',
        ));
        $this->client->submit($form);
    }
    
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
        $crawler = $this->client->request('GET', '/liturgy_text/assemble');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        //this was used to check the date of the redirection but sometimes the CNBB is offline.
        $today = (new \DateTime())->format('Y-m-d');
        $crawler = $this->client->submitForm('Obter Texto');
        $this->assertTrue(
            $this->client->getResponse()->isRedirect()
        );

        // now lets test the getText action.
        //    First makes sure the route is restricted for pdf, rtf,
        //         formats and the sources: CNBB and Igreja_Santa_Ines
                $today = (new \DateTime())->format('Y-m-d');
        $this->client->request('GET', '/assembler/text/pdf/unknown/'.$today.'/');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/assembler/text/algo/CNBB/'.$today.'/');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', '/assembler/text/PDF/Igreja_Santa_Ines/21000-12-10/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html div.warning', 'Texto não encontrado.');

        $this->assertSelectorTextContains('html div.error_detail', 'Fonte : Igreja_Santa_Ines');

        $this->assertEquals(
            2,
            $crawler->filter('html div.error_detail')->count()
        );
        
        $this->assertEquals(
            1,
            $crawler->filter('html a:contains("voltar")')->count()
        );
        $this->client->clickLink('voltar');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/assembler/text/DOCX/CNBB/1900-01-01/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html div.warning', 'Texto não encontrado.');

        $this->assertSelectorTextContains('html div.error_detail', 'Fonte : CNBB');

        $this->assertEquals(
            2,
            $crawler->filter('html div.error_detail')->count()
        );
        
        $this->assertEquals(
            1,
            $crawler->filter('html a:contains("voltar")')->count()
        );
        $this->client->clickLink('voltar');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
