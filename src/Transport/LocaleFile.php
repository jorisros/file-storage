<?php

namespace JorisRos\FileStorage\Transport;

use JorisRos\FileStorage\TransportInterface;
use Psr\Log\LoggerInterface;

class LocaleFile implements TransportInterface
{
    /** @var LoggerInterface  */
    private $logger;

    /** @var string */
    private $baseDirectory;

    public function __construct($parameters, LoggerInterface $logger)
    {
        $this->logger = $logger;

        if (isset($parameters['baseDirectory'])) {
            $this->baseDirectory = $parameters['baseDirectory'];
        } else {
            throw new \Exception("Conditions are not valid");
        }
    }

    public function getFileContent(string $location)
    {
        $path = $this->baseDirectory . DIRECTORY_SEPARATOR . $location;

        if (file_exists($path)) {
            $this->logger->info(sprintf("Read %s from local file system", $path));

            return file_get_contents($path);
        } else {
            $this->logger->error(sprintf("File %s does not exists", $location));

            //@TODO Add location to the exception
            throw new \Exception("File does not exists");
        }
    }

    public function setFileContent(string $location, $data): string
    {
        if (!file_exists($this->baseDirectory)) {
            mkdir($this->baseDirectory, '0777', true);
        }
        $path = $this->baseDirectory . DIRECTORY_SEPARATOR . $location;
        file_put_contents($path, $data);

        return $path;
    }
}
