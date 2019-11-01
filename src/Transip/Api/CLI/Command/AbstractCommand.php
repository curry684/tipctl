<?php

namespace Transip\Api\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Transip\Api\Client\TransipAPI;

abstract class AbstractCommand extends Command
{
    /**
     * @var TransipAPI $transipApi
     */
    private $transipApi;

    public function __construct(string $name = null)
    {
        $apiurl = '';
        $token  = '';

        $this->transipApi = new TransipAPI($token, $apiurl);
        parent::__construct($name);
    }

    public function getTransipApi(): TransipAPI
    {
        return $this->transipApi;
    }
}