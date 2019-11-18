<?php

namespace Transip\Api\CLI\Command\PrivateNetwork;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\CLI\Command\AbstractCommand;
use Transip\Api\CLI\Command\Field;

class SetDescription extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('PrivateNetwork:setDescription')
            ->setDescription('Set a new description to a private network')
            ->addArgument(Field::PRIVATENETWORK_NAME, InputArgument::REQUIRED, Field::PRIVATENETWORK_NAME__DESC)
            ->addArgument(Field::PRIVATENETWORK_DESCRIPTION, InputArgument::REQUIRED, Field::PRIVATENETWORK_DESCRIPTION__DESC);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $privateNetworkName = $input->getArgument(Field::PRIVATENETWORK_NAME);
        $privateNetworkDescription = $input->getArgument(Field::PRIVATENETWORK_DESCRIPTION);

        $privateNetwork = $this->getTransipApi()->privateNetworks()->getByName($privateNetworkName);
        $privateNetwork->setDescription($privateNetworkDescription);

        $this->getTransipApi()->privateNetworks()->update($privateNetwork);
    }
}
