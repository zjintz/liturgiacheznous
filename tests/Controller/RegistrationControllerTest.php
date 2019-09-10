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
        $crawler =$this->client->request('GET', '/register/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('Registar')->form();
        // set some values
        $form['fos_user_registration_form[firstName]'] = 'name';
        $form['fos_user_registration_form[lastName]'] = 'lm';
        $form['fos_user_registration_form[plainPassword][first]'] = '1111111';
        $form['fos_user_registration_form[plainPassword][second]'] = '1111111';
        $form['fos_user_registration_form[email]'] = 'test@no.com';
        $form['fos_user_registration_form[headquarter][name]'] = 'hqname';
        $form['fos_user_registration_form[headquarter][city]'] = 'hqcity';
        $form['fos_user_registration_form[headquarter][country]'] = 'CO';
        $crawler = $this->client->submit($form);
        $this->assertRedirect("/register/confirmed");
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
