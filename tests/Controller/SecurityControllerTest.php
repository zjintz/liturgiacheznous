<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserTestFixtures;
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
     * redirect the user to the login page. And of course deny access. If the
     * credentials are right, it should redirect to "/".
     *
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $this->assertLoginContent($crawler);
        $form = $crawler->selectButton('Log in')->form();
        //trying with wrong credentials
        $form['_username'] = 'fakeadmin';
        $form['_password'] = 'fakeadmin';
        $crawler = $this->client->submit($form);
        $this->assertRedirect('http://localhost/login');

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
                'p:contains("Authentication")'
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
                'button:contains("Log in")'
            )->count()
        );
    }
}
