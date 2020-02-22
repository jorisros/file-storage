<?php

namespace JorisRos\FileStorage\Test;

use JorisRos\FileStorage\FileStorageService;
use JorisRos\FileStorage\TransportInterface;
use PHPUnit\Framework\TestCase;

class FileStorageServiceTest extends TestCase
{
    /**
     * Returns a Mock of the logger
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getLogMock()
    {
        return $this->getMockBuilder(\Psr\Log\LoggerInterface::class)->getMock();
    }

    /**
     * Returns a Mock of the transport
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getTransportMock()
    {
        return $this->getMockBuilder(TransportInterface::class)
            ->setConstructorArgs([[], $this->getLogMock()])
            ->getMock();
    }

    /**
     * Test the retreival a a file trough a transport layer
     */
    public function testGetFileContent()
    {
        $transportMock = $this->getTransportMock();

        $transportMock->method('getFileContent')
            ->with('/dev/null')
            ->willReturn('String from file');

        $fileStorageService = new FileStorageService($transportMock);

        $data = $fileStorageService->getFileContent('/dev/null');
        $this->assertEquals('String from file', $data);
    }

    /**
     * Test if a file doesnt exists anymore
     */
    public function testGetFileContentWithNotExistingFile()
    {
        $transportMock = $this->getTransportMock();

        $this->expectExceptionMessage("File does not exists");

        $transportMock->method('getFileContent')
            ->willThrowException(new \Exception("File does not exists"));

        $fileStorageService = new FileStorageService($transportMock);

        $fileStorageService->getFileContent('/dev/does_not_exists');
    }
}
