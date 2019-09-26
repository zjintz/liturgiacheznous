<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserTestFixtures;
use App\DataFixtures\UserNotEnabledTestFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * Functional tests of the security aspects of the system.
 *
 */
class SecurityControllerTest extends WebTestCase
{

    use FixturesTrait;
    
    protected $client;
    /**
     * Anonymous (unauthenticated) users should be always redirected to the login page.
     *
     * @return void
     */
    public function testAnonAccess()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/dashboard');
        $this->assertRedirect('http://localhost/login');
        $crawler = $this->client->followRedirect();
        $this->assertLoginContent($crawler);
        $this->client->request('GET', '/logout');
        $this->assertRedirect('http://localhost/login');
        $crawler = $this->client->followRedirect();
        $this->assertLoginContent($crawler);
        $this->client->request('GET', '/noexiste');
        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    /**
     * When the credentials user or pasword fail the security system have to
     * redirect the user to the login page. And of course deny access. 
     *
     *
     * @return void
     */
    public function testFailedLogin()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $this->assertLoginContent($crawler);
        $form = $crawler->selectButton('Entrar')->form();
        //trying with wrong credentials
        $form['_username'] = 'fakeadmin';
        $form['_password'] = 'fakeadmin';
        $crawler = $this->client->submit($form);
        $this->assertRedirect('http://localhost/login');

    }

    /**
     * Tests the basic login and logout mechanics.
     *
     */
    public function testLoginLogout()
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
        $this->assertRedirect('http://localhost/');
        $crawler = $this->client->followRedirect();
        // now logout
        $crawler = $this->client->request('GET', '/logout');
        $this->assertRedirect('http://localhost/login');
                
    }

    /**
     * Tests acces denied when the user is not neabled.
     *
     */
    public function testNotEnabled()
    {
        $this->loadFixtures([UserNotEnabledTestFixtures::class]);
        $this->client = static::createClient();
        $this->client->setServerParameters([]);

        //now login:
        $crawler = $this->client->request('GET', '/');
        $crawler=$this->client->followRedirect();
        $form = $crawler->selectButton('Entrar')->form(array(
            '_username'  => 'userne@test.com',
            '_password'  => 'testnePass',
        ));
        $this->client->submit($form);
        $this->assertRedirect('http://localhost/login');
        $crawler = $this->client->followRedirect();
                
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
    
    private function assertLoginContent($crawler)
    {
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals(
            1,
            $crawler->filter(
                'p:contains("Autenticação")'
            )->count()
        );
        $this->assertEquals(
            4,
            $crawler->filter(
                'input'
            )->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter(
                'button:contains("Entrar")'
            )->count()
        );
    }
}
