<?php

namespace Blog\TodoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskController extends WebTestCase
{
    /**
     * Returns all tasks from logged User
     */
    public function testGetTasks()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks');

        $this->assertTrue($crawler->filter('html:contains("msg")')->count() > 0);
    }

}
