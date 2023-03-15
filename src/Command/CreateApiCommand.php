<?php

namespace App\Command;

use App\Entity\Character;
use App\Entity\Location;
use App\Entity\Origin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:create-api',
    description: 'Creates a api',
)]
class CreateApiCommand extends Command
{
    protected EntityManagerInterface $entityManager;

    private $client;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $client)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->recursiveImport($output, "https://rickandmortyapi.com/api/character");

        $output->writeln('Api created!');
        return Command::SUCCESS;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function recursiveImport(OutputInterface $output, string $url)
    {
        $response = $this->client->request(
            'GET',
            $url
        );
        $content = $response->getContent();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_close($ch);

        $json = json_decode($content, true);

        foreach ($json['results'] as $item) {
            $name = $item['name'];
            $status = $item['status'];
            $species = $item['species'];
            $type = $item['type'];
            $gender = $item['gender'];
            $image = $item['image'];

            $character = new Character();
            
            $character->setName($name);
            $output->writeln($name);
            $character->setStatus($status);
            $character->setSpecies($species);
            $character->setType($type);
            $character->setGender($gender);
            $character->setImage($image);
            $output->writeln($image);

            $origin = $this->entityManager->getRepository(Origin::class)->findOneBy(['name' => $item['origin']['name']]);
            if ($origin) {
                $character->setOrigin($origin);
            } else {
                $origin = new Origin();
                $origin->setName($item['origin']['name']);
                $origin->setUrl($item['origin']['url']);

                $character->setOrigin($origin);

                $this->entityManager->persist($origin);
                $this->entityManager->flush();
            }

            $location = $this->entityManager->getRepository(Location::class)->findOneBy(['name' => $item['location']['name']]);
            if ($location) {
                $character->setLocation($location);
            } else {
                $location = new Location();
                $location->setName($item['location']['name']);
                $location->setUrl($item['location']['url']);

                $character->setLocation($location);

                $this->entityManager->persist($location);
                $this->entityManager->flush();
            }

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            if ($json['info']['next'] != null) {
                $this->recursiveImport($output, $json['info']['next']);
            }
        }
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a user...');
    }
}