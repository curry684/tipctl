<?php

namespace Transip\Api\CLI\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class BigStorage extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('BigStorage')
            ->setDescription('TransIP Bigstorage')
            ->setHelp('Bigstorage for TransIP Vpses')
            ->addArgument("action", InputArgument::REQUIRED, "")
            ->addUsage("getAll")
            ->addUsage("getByName")
            ->addUsage("order")
            ->addUsage("upgrade")
            ->addUsage("setDescription")
            ->addUsage("attachToVps")
            ->addUsage("detachVps")
            ->addUsage("getBackupsByBigstorageName")
            ->addUsage("cancel")
            ->addArgument("args", InputArgument::IS_ARRAY, "Optional arguments");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('action')) {
            case "getAll":
                $bigStorages = $this->getTransipApi()->bigStorages()->getAll();
                $output->writeln(print_r($bigStorages, 1));
                break;
            case "getByName":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 1) {
                    throw new Exception("BigstorageName is required");
                }
                $bigStorage = $this->getTransipApi()->bigStorages()->getByName($arguments[0]);
                $output->writeln(print_r($bigStorage, 1));
                break;
            case "order":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 1) {
                    throw new Exception("size is required offsiteBackups, vpsName, ");
                }

                $size            = $arguments[0];
                $offsiteBackups  = filter_var($arguments[1] ?? true, FILTER_VALIDATE_BOOLEAN);
                $availabiltyZone = $arguments[2] ?? '';
                $vpsName         = $arguments[3] ?? '';

                $this->getTransipApi()->bigStorages()->order($size, $offsiteBackups, $availabiltyZone, $vpsName);
                break;
            case "upgrade":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 2) {
                    throw new Exception("bigStorageName and size is required, offsiteBackup optional");
                }

                $bigStorageName = $arguments[0];
                $size           = $arguments[1];
                $offsiteBackups = null;
                if (isset($arguments[2])) {
                    $offsiteBackups = filter_var($arguments[2], FILTER_VALIDATE_BOOLEAN);
                }
                $this->getTransipApi()->bigStorages()->upgrade($bigStorageName, $size, $offsiteBackups);
                break;
            case "setDescription":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 2) {
                    throw new Exception("BigstorageName and description is required");
                }
                $bigStorage = $this->getTransipApi()->bigStorages()->getByName($arguments[0]);
                $bigStorage->setDescription($arguments[1]);
                $this->getTransipApi()->bigStorages()->update($bigStorage);
                break;
            case "attachVps":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 2) {
                    throw new Exception("BigstorageName and vpsName is required");
                }
                $bigStorage = $this->getTransipApi()->bigStorages()->getByName($arguments[0]);
                $bigStorage->setVpsName($arguments[1]);
                $this->getTransipApi()->bigStorages()->update($bigStorage);
                break;
            case "detachVps":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 1) {
                    throw new Exception("BigstorageName is required");
                }
                $bigStorage = $this->getTransipApi()->bigStorages()->getByName($arguments[0]);
                $bigStorage->setVpsName('');
                $this->getTransipApi()->bigStorages()->update($bigStorage);
                break;
            case "cancel":
                $arguments = $input->getArgument('args');
                if (count($arguments) < 2) {
                    throw new Exception("BigstorageName and cancellation time (end|immediately) is required");
                }
                $this->getTransipApi()->bigStorages()->cancel($arguments[0], $arguments[1]);
                break;
            default:
                throw new Exception("invalid action given '{$input->getArgument('action')}'");
        }
    }
}