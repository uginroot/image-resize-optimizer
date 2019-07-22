<?php


namespace Uginroot;


use Spatie\ImageOptimizer\Optimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Uginroot\Exception\ImageResizeBadFormatException;

class ImageResizeOptimizer extends ImageResize
{
    protected $optimizer;

    public function __construct($image, string $content = null)
    {
        parent::__construct($image, $content);
        $this->optimizer = OptimizerChainFactory::create();
    }

    /**
     * @param Optimizer $optimizer
     */
    public function addOptimize(Optimizer $optimizer){
        $this->optimizer->addOptimizer($optimizer);
    }


    /**
     * @param string $path
     * @param int $format
     * @param bool $overwrite
     * @param int $mode
     * @return static
     * @throws Exception\ImageResizeBadFormatException
     * @throws Exception\ImageResizeFileAlreadyExistException
     */
    public function save(string $path, int $format = ImageResize::FORMAT_JPEG, $overwrite = false, int $mode = 0666)
    {
        parent::save($path, $format, $overwrite, $mode);
        $this->optimizer->optimize($path);
        return $this;
    }

    /**
     * @param int $format
     * @throws Exception\ImageResizeBadFormatException
     * @throws Exception\ImageResizeFileAlreadyExistException
     */
    public function print(int $format = self::FORMAT_JPEG): void
    {
        echo $this->getContent($format);
    }

    /**
     * @param int $format
     * @return string|void
     * @throws Exception\ImageResizeBadFormatException
     * @throws Exception\ImageResizeFileAlreadyExistException
     */
    public function getContent(int $format = self::FORMAT_JPEG)
    {
        switch ($format) {
            case static::FORMAT_JPEG:
                $extension = 'jpg';
                break;
            case static::FORMAT_PNG:
                $extension = 'png';
                break;
            case static::FORMAT_WEBP:
                $extension = 'webp';
                break;
            default:
                throw new ImageResizeBadFormatException();
        }
        $temp = tempnam('', '');
        rename($temp, $temp . '.' . $extension);
        $this->save($temp, $format, true, 0666);
        $content = file_get_contents($temp);
        unlink($temp);
        return $content;
    }
}