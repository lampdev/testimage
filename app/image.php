<?php

class Image
{
    private $file;
    private $imgWidth;
    private $imgHeight;
    private $imgExtension;

    public function __construct(array $file)
    {
        $this->file = $file;
        if (!file_exists($this->file['tmp_name'])) {
            throw new Exception('Uploaded file does not exists.');
        }

        [
            $this->imgWidth,
            $this->imgHeight
        ] = getimagesize($this->file['tmp_name']);

        $pathParts = pathinfo($this->file['name']);

        $this->imgExtension = (
            empty($pathParts['extension'])
            ? ''
            : $pathParts['extension']
        );
    }

    public function checkExtension(array $allowedExtensions)
    {
        if (!in_array($this->imgExtension, $allowedExtensions)) {
            throw new Exception('File extension is not allowed.');
        }
    }

    public function checkAspectRatio(
        float $allowedRatio,
        float $allowedRatioDiff
    ) {
        $allowedRatioAccuracy = strlen(substr(strrchr($allowedRatio, "."), 1));

        if (
            abs(
                round($this->imgWidth / $this->imgHeight, $allowedRatioAccuracy)
                - $allowedRatio
            ) > $allowedRatioDiff
        ) {
            throw new Exception('File aspect ratio is not allowed.');
        }
    }


    public function crop(
        int $pageNumber,
        int $tylesPerX,
        int $tylesPerY,
        string $destinationPath
    ) {
        /**
         * Center mode: none. Split directly without find the center
         * @const IMAGE_SPLITTER_CENTER_NONE
         */
        define('IMAGE_SPLITTER_CENTER_NONE', 0);

        /**
         * Center mode: normal. Make a rectangular canvas which can be covered by
         * integral number of the tiles, then put the source image in the center
         * @const IMAGE_SPLITTER_CENTER_NORMAL
         */
        define('IMAGE_SPLITTER_CENTER_NORMAL', 1);

        /**
         * Center mode: square(default for the centerMode attribute). 
         * Make a square canvas which can be covered by integral number of the tiles,
         * then put the source image in the center
         * @const IMAGE_SPLITTER_CENTER_SQUARE
         */
        define('IMAGE_SPLITTER_CENTER_SQUARE', 2);

        require_once(App::getPath('app/imagesplitter.php'));

        $imgSplitter = new ImageSplitter();
        $imgSplitter->load($this->file['tmp_name']);
        // $imgSplitter->tileWidth = ceil($this->imgWidth / $tylesPerX);
        // $imgSplitter->tileHeight = ceil($this->imgHeight / $tylesPerY);
        $imgSplitter->getAllTiles(
            $destinationPath,
            'zoom_' . $pageNumber//,
            // '.' . $this->imgExtension
        );
    }
}
