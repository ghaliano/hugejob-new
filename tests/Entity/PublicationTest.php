<?php
namespace App\Tests\Entity;

use App\Entity\Feed;
use App\Entity\Publication;
use PHPUnit\Framework\TestCase;

class PublicationTest extends TestCase
{
    public function testAddFeed()
    {
        $publication = new Publication();
        $publication->addFeed(new Feed());
        $publication->addFeed(new Feed());
        $this->assertEquals(2, count($publication->getFeeds()));
    }
}
