<?php

namespace App\Tests\Controller;

use App\Entity\Voyage;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VoyageControllerTest extends WebTestCase{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $voyageRepository;
    private string $path = '/voyage/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->voyageRepository = $this->manager->getRepository(Voyage::class);

        foreach ($this->voyageRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voyage index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'voyage[destination]' => 'Testing',
            'voyage[date_depart]' => 'Testing',
            'voyage[date_retour]' => 'Testing',
            'voyage[budget]' => 'Testing',
            'voyage[etat]' => 'Testing',
            'voyage[title]' => 'Testing',
            'voyage[userId]' => 'Testing',
            'voyage[numeroVol]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->voyageRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voyage();
        $fixture->setDestination('My Title');
        $fixture->setDate_depart('My Title');
        $fixture->setDate_retour('My Title');
        $fixture->setBudget('My Title');
        $fixture->setEtat('My Title');
        $fixture->setTitle('My Title');
        $fixture->setUserId('My Title');
        $fixture->setNumeroVol('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voyage');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voyage();
        $fixture->setDestination('Value');
        $fixture->setDate_depart('Value');
        $fixture->setDate_retour('Value');
        $fixture->setBudget('Value');
        $fixture->setEtat('Value');
        $fixture->setTitle('Value');
        $fixture->setUserId('Value');
        $fixture->setNumeroVol('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voyage[destination]' => 'Something New',
            'voyage[date_depart]' => 'Something New',
            'voyage[date_retour]' => 'Something New',
            'voyage[budget]' => 'Something New',
            'voyage[etat]' => 'Something New',
            'voyage[title]' => 'Something New',
            'voyage[userId]' => 'Something New',
            'voyage[numeroVol]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voyage/');

        $fixture = $this->voyageRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDestination());
        self::assertSame('Something New', $fixture[0]->getDate_depart());
        self::assertSame('Something New', $fixture[0]->getDate_retour());
        self::assertSame('Something New', $fixture[0]->getBudget());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getUserId());
        self::assertSame('Something New', $fixture[0]->getNumeroVol());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voyage();
        $fixture->setDestination('Value');
        $fixture->setDate_depart('Value');
        $fixture->setDate_retour('Value');
        $fixture->setBudget('Value');
        $fixture->setEtat('Value');
        $fixture->setTitle('Value');
        $fixture->setUserId('Value');
        $fixture->setNumeroVol('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/voyage/');
        self::assertSame(0, $this->voyageRepository->count([]));
    }
}
