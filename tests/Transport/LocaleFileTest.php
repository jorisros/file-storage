<?php

namespace Transport;

use JorisRos\FileStorage\Transport\LocaleFile;
use PHPUnit\Framework\TestCase;

class LocaleFileTest extends TestCase
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

    public function testSetFileContent()
    {
        $this->assertEquals(true, true);

        $tempDirectory = sys_get_temp_dir();

        $this->assertTrue(is_dir($tempDirectory));

        $localFileTransport = new LocaleFile([
            'baseDirectory' => $tempDirectory
        ], $this->getLogMock());

        $location = $localFileTransport->setFileContent('testfile', 'Temp content');

        $this->assertTrue(file_exists($location));
        $this->assertEquals(
            'Temp content',
            file_get_contents($tempDirectory . DIRECTORY_SEPARATOR . 'testfile')
        );
    }

    public function testInvalidSettings()
    {
        $this->expectExceptionMessage("Conditions are not valid");

        $localFileTransport = new LocaleFile([], $this->getLogMock());
        $data = $localFileTransport->getFileContent('testfile');
    }

    public function testGetFileContent()
    {
        $tempDirectory = sys_get_temp_dir();

        file_put_contents($tempDirectory . DIRECTORY_SEPARATOR . 'readfile', 'Temp content');

        $localFileTransport = new LocaleFile([
            'baseDirectory' => $tempDirectory
        ], $this->getLogMock());

        $data = $localFileTransport->getFileContent('readfile');

        $this->assertEquals('Temp content', $data);
    }

    public function testGetList()
    {
        $localFileTransport = new LocaleFile([
            'baseDirectory' => __DIR__ . '/../../testdir'
        ], $this->getLogMock());

        mkdir(__DIR__ . '/../../testdir');
        for ($i = 0; $i < 10; $i++) {
            touch(__DIR__ . '/../../testdir/testfile_' . $i);
        }
        $listOfFiles = $localFileTransport->getList();
        $this->assertIsArray($listOfFiles);

        foreach ($listOfFiles as $file) {
            $this->assertNotEquals(".", $file);
            $this->assertNotEquals("..", $file);
        }
        $this->assertCount(10, $listOfFiles);
    }

    public function tearDown(): void
    {
        $tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phpunit' . DIRECTORY_SEPARATOR . 'testfile';
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        $tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'readfile';
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        $this->removeDirectory(__DIR__ . '/../../testdir');
    }

    private function removeDirectory($path)
    {
        if (!file_exists($path)) {
            return;
        }

        $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path) . '/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if ($file == $path . '/.' || $file == $path . '/..') {
                continue;
            } // skip special dir entries
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
}
