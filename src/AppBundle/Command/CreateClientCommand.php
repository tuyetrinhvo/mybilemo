<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('create:new:client')
            ->setDescription('Create a new client')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris([$this->getContainer()->get('kernel')->getRootDir()]);
        $client->setAllowedGrantTypes(['password', 'refresh_token']);
        $clientManager->updateClient($client);

        $output->writeln('Your client_id : ' . $client->getPublicId());
        $output->writeln('Your client_secret : ' . $client->getSecret());
    }
}