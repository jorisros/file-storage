<?php

namespace JorisRos\FileStorage;

use JorisRos\FileStorage\Interfaces\TransportInterface;

/**
 * Class FileStorageService
 *
 * @package JorisRos\FileStorage
 */
final class FileStorageService
{
    /** @var TransportInterface */
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param string $location
     * @return mixed
     */
    public function getFileContent(string $location)
    {
        return $this->transport->getFileContent($location);
    }

    /**
     * @param string $location
     * @param $data
     */
    public function setFileContent(string $location, $data)
    {
        $this->transport->setFileContent($location, $data);
    }
}