<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new HttpBrowser();

        $crawler = $client->request('GET', 'https://vi.wikipedia.org/wiki/Th%E1%BB%83_lo%E1%BA%A1i_phim');

        $crawler->filter('li')->each(function (Crawler $node) use ($manager) {
            $part = explode(': ', $node->text());
            if (2 === count($part) && str_starts_with($part[0], 'Phim')) {
                [$name, $description] = $part;
                // remove '(...)' from name
                $name = explode('(', $name)[0];

                $genre = new Genre();
                $genre->setName($name);
                $genre->setDescription($description);

                $manager->persist($genre);
            }
        });

        $manager->flush();
    }
}
