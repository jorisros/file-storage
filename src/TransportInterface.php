<?php

namespace JorisRos\FileStorage;

use Psr\Log\LoggerInterface;

interface TransportInterface
{
    public function __construct($parameters, LoggerInterface $logger);

    public function getFileContent(string $location);

    public function setFileContent(string $location, $data): string;

    public function getList(): array;
}
