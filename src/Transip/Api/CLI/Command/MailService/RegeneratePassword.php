<?php

namespace Transip\Api\CLI\Command\MailService;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\CLI\Command\AbstractCommand;

class RegeneratePassword extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('MailService:regeneratePassword')
            ->setDescription('Get a new password for the mail service');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getTransipApi()->mailService()->regenerateMailServicePassword();
        $mailServiceInformation = $this->getTransipApi()->mailService()->getMailServiceInformation();

        $output->writeln(print_r($mailServiceInformation, 1));
    }
}