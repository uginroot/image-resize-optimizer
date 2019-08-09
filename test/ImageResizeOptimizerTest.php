<?php


namespace Uginroot\Test;


use Exception\ImageResizeImpossibleToCreateDirectoryException;
use PHPUnit\Framework\TestCase;
use Uginroot\Exception\ImageResizeBadContentException;
use Uginroot\Exception\ImageResizeBadFormatException;
use Uginroot\Exception\ImageResizeBadResourceException;
use Uginroot\Exception\ImageResizeFileAlreadyExistException;
use Uginroot\Exception\ImageResizeFileNotExistException;
use Uginroot\ImageResize;
use Uginroot\ImageResizeOptimizer;

class ImageResizeOptimizerTest extends TestCase
{
    private $pathIn = __DIR__ . '/image/horizontal.jpg';
    private $pathOut = __DIR__ . '/temp/horizontal.jpg';
    private $pathOutWithoutOptimize = __DIR__ . '/temp/horizontalWithoutOptimize.jpg';



    public function tearDown(): void
    {
        unlink($this->pathOut);
        unlink($this->pathOutWithoutOptimize);
    }

    /**
     * @throws ImageResizeBadContentException
     * @throws ImageResizeBadFormatException
     * @throws ImageResizeBadResourceException
     * @throws ImageResizeFileAlreadyExistException
     * @throws ImageResizeFileNotExistException
     * @throws ImageResizeImpossibleToCreateDirectoryException
     */
    public function testSave()
    {

        $image = ImageResizeOptimizer::createFromPath($this->pathIn);
        $image->save($this->pathOut, ImageResize::FORMAT_JPEG, true, 0666);

        $sizeOriginal = filesize($this->pathIn);
        $sizeOptimize = filesize($this->pathOut);

        $this->assertTrue($sizeOriginal > $sizeOptimize);

        $image = ImageResize::createFromPath($this->pathIn);
        $image->save($this->pathOutWithoutOptimize, ImageResize::FORMAT_JPEG, true, 0666);

        $sizeWithoutOptimize = filesize($this->pathOutWithoutOptimize);

        $this->assertTrue($sizeWithoutOptimize > $sizeOptimize);
    }
}