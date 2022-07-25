<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\BrowserKit\HttpBrowser;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new HttpBrowser();
        $crawler = $client->request('GET', 'https://vlxx.sex');
        /** @var list<string> */
        $videos = $crawler->filterXPath('//div[contains(@id, "video-")]')->evaluate('substring-after(@id, "-")');
        array_shift($videos);

        foreach ($videos as $id) {
            $crawler = $client->request('GET', 'https://vlxx.sex/'.$id);
            $title = $crawler->filter('#page-title')->first()->text();
            echo $title,PHP_EOL;
            $description = $crawler->filter('.video-description')->first()->text();
            $movie = new Movie();
            $movie->setName($title);
            $movie->setDescription($description);
            $movie->setPoster('https://vlxx.sex/img2/'.$id.'.jpg');
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
