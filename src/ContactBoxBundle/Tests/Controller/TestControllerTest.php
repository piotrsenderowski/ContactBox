<?php

namespace ContactBoxBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testSome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/some');
    }

}
