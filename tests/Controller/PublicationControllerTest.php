<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 2/21/2019
 * Time: 4:05 PM
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicationControllerTest extends WebTestCase
{
    public function testGetPublications()
    {
        $client = static::createClient();
        $client->request('GET', '/api/publications.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            "application/json; charset=utf-8",
            $client->getResponse()->headers->get('Content-Type')
        );
    }
}