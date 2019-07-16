<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional tests of the user registration.
 *
 */
class RegistrationControllerTest extends WebTestCase
{

    use FixturesTrait;
    
    protected $client;

    /**
     * Anonymous (unauthenticated) users can get to the registration page of course.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->client = static::createClient();
        $this->loadFixtures([]);
        $crawler =$this->client->request('GET', '/en/register');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('Register')->form();
        // set some values
        $form['registration_form[name]'] = 'name';
        $form['registration_form[lastName]'] = 'lm';
        $form['registration_form[plainPassword]'] = '1111111';
        $form['registration_form[email]'] = 'test@no.com';
        $form['registration_form[headquarter][name]'] = 'hqname';
        $form['registration_form[headquarter][city]'] = 'hqcity';
        $form['registration_form[headquarter][country]'] = 'CO';
        $crawler = $this->client->submit($form);
        $this->assertRedirect("/en/login/");
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        // submit the form
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
