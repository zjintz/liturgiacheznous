<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional tests of the user registration.
 *
 */
class RegistrationControllerTest extends WebTestCase
{

    protected $client;

    /**
     * Anonymous (unauthenticated) users can get to the registration page of course.
     *
     * @return void
     */
    public function testAnonAccess()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/en/register/');
        echo $this->client->getResponse()->getContent();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
