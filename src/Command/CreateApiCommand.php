<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-api',
    description: 'Creates a api',
)]
class CreateApiCommand extends Command
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->recursiveImport($output);

        return Command::SUCCESS;
    }

    private function recursiveImport(OutputInterface $output)
    {
        $ch = curl_init('https://rickandmortyapi.com/api/character');
        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result, true);

        foreach ($json['results'] as $item) {
            dd($item['name']);
            //$output->writeln($item['name']);

            /*$ch2 = curl_init($item['origin']['url']);
            $result2 = curl_exec($ch2);
            curl_close($ch2);*/
        }
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a user...');
    }
}