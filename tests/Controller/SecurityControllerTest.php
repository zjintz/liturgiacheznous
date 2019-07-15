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
        $this->client->request('GET', '/en/assembler/');
        $this->assertRedirect('/en/login/');
        $crawler = $this->client->followRedirect();
        $this->assertLoginContent($crawler);
        $this->client->request('GET', '/logout');
        $this->assertRedirect('http://localhost/en/login/');
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
        $classes = array(
            UserTestFixtures::class
        );
        $this->loadFixtures($classes);
        $crawler = $this->client->request('GET', '/en/login');
        $crawler = $this->client->followRedirect();
        $this->assertLoginContent($crawler);
        $form = $crawler->selectButton('Sign in')->form();
        //trying with wrong credentials
        $form['email'] = 'fakeadmin';
        $form['password'] = 'fakeadmin';
        $crawler = $this->client->submit($form);
        $this->assertRedirect('/en/login/');
        //Now asserting with the right credentials.
        $crawler = $this->client->request('GET', '/en/login');
        $this->assertRedirect('http://localhost/en/login/');
        $crawler = $this->client->followRedirect();
        $this->assertLoginContent($crawler);
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'tester@test.com';
        $form['password'] = 'test';
        /*        $crawler = $this->client->submit($form);
        $this->assertRedirect('/en/');
        //everything ok so far. what if I go to login once logged in?
        // it should go to /.
        $crawler = $this->client->request('GET', '/en/login');
        $this->assertRedirect('/en/');
        //now assert that logout goes to /login
        $crawler = $this->client->request('GET', '/logout');
        $this->assertRedirect('http://localhost/en/login');*/
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
                'label:contains("Email")'
            )->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter(
                'label:contains("Password")'
            )->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter(
                'button:contains("Sign in")'
            )->count()
        );
    }
}
