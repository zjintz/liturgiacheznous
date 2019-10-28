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
 * @brief      Functional tests of the DemoMailerController class.
 *
 * @details    Makes sure the demo mailer works as expected.
 *
 */
class DemoMailerControllerTest extends WebTestCase
{
    use FixturesTrait;
    
    private $client = null;

    public function setUp()
    {

    }
    
    /**
     * \brief      Test the demo_mail route.
     *
     * \details    First makes sure the route responds as expected when 
     *             there is no user logged in.
     *             
     *
     */
    public function testDemoMailNoLogin()
    {
        //first try using the route without login
        $this->client = static::createClient();
        $this->client->request('GET', '/demo/mail/weekly/ALL/ALL');
        $this->assertRedirect('http://localhost/login');
    }

    /**
     * \brief      Test the demo_mail route.
     *
     * \details    First makes sure the route responds as expected when 
     *             there is no user logged in.
     *             
     *
     */
    public function testDemoMail()
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
        
        $this->client->request('GET', 'http://localhost/demo/mail/weekly/ALL/ALL/');
        $this->assertResponseIsSuccessful($this->client->getResponse());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['outcome' => 'success']),
            $this->client->getResponse()->getContent()
        );
    }
    

    private function assertRedirect($destiny)
    {
        $this->assertTrue(
            $this->client->getResponse()->isRedirect()
        );
        $this->assertTrue(
            $this->client->getResponse()->isRedirect($destiny)
        );
    }
}
