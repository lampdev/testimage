<?php

class App
{
    const DEFAULT_ACTION = 'index';
    const ACTION_PREFIX = 'action_';
    const RESULTS_DIR = 'img';
    const ALLOWED_ASPECT_RATIO = 1.41; // sqrt(2) per A4 specification
    const ALLOWED_AR_DIFF = 0.15; // hard to find exact A4 picture, so set diff value
    const ALLOWED_EXTENSIONS = [ 'png', 'jpg' ];
    const TYLES_PER_X = 8;
    const TYLES_PER_Y = 6;

    private static $sessionId;

    public static function getPath(string $destination)
    {
        // didn't want to spent time to add composer and its autoloader so manually require the files:
        return (
            dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $destination
        );
    }

    public function __call($action, $args)
    {
        return $this->action_error('Page not found', 404);
    }

    public static function getSessionId()
    {
        if (is_null(self::$sessionId)) {
            self::$sessionId = time() . '_' . rand(1, 1000);
        }

        return self::$sessionId;
    }

    public static function getRequestedSessionId()
    {
        if (empty($_GET['session'])) {
            throw new Exception('Session GET Param is required!');
        }

        return $_GET['session'];
    }

    public static function handle($action)
    {
        $app = new self();
        try {
            $app->$action();
        } catch (Exception $e) {
            return (
                $app->action_error($e->getMessage())
            );
        }
    }

    public function action_error(
        string $message = 'Undefined Error',
        int $statusCode = 400
    ) {
        http_response_code(404);
        die($message);
    }

    public function action_index()
    {
        require_once(self::getPath('app/view.php'));
    }

    public function action_upload()
    {
        require_once(self::getPath('app/image.php'));
        $sessionId = self::getRequestedSessionId();
        if (empty($_FILES['file'])) {
            throw new Exception('No file provided');
        }

        $imgsDir = self::getPath("img/{$sessionId}");

        if (!file_exists($imgsDir)) {
            mkdir($imgsDir);
            $pageNumber = 1;
        } else {
            $files = scandir($imgsDir, SCANDIR_SORT_DESCENDING);
            if (empty($files)) {
                $pageNumber = 2;
            } else {
                $lastFile = $files[0];
                if (!preg_match('/^(\d)_(\d+)_(\d+)_(\d)\.\S{3,4}$/', $lastFile, $m)) {
                    $pageNumber = 2;
                } else {
                    $pageNumber = 1 + intval($m[2]);
                }
            }
        }

        $image = new Image($_FILES['file']);
        $image->checkExtension(self::ALLOWED_EXTENSIONS);
        $image->checkAspectRatio(
            self::ALLOWED_ASPECT_RATIO,
            self::ALLOWED_AR_DIFF
        );
        $image->crop(
            $pageNumber,
            self::TYLES_PER_X,
            self::TYLES_PER_Y,
            $imgsDir
        );
    }

    public function action_scan()
    {
        $dir = self::getPath('img/'.self::getRequestedSessionId());
        if (!file_exists($dir)) {
            throw new Exception('There were no successfull image uploads for your session!');
        }

        $images = [];

        foreach (scandir($dir) as $fileName) {
            if (!preg_match('/^(\d)_(\d+)_(\d+)_(\d)\.\S{3,4}$/', $fileName, $m)) {
                continue;
            }

            [
                $fullMath,
                $zoom,
                $page,
                $row,
                $col
            ] = $m;

            if (empty($images[$zoom])) {
                $images[$zoom] = [];
            }

            if (empty($images[$zoom][$page])) {
                $images[$zoom][$page] = [];
            }

            if (empty($images[$zoom][$page][$row])) {
                $images[$zoom][$page][$row] = [];
            }

            $images[$zoom][$page][$row][$col] = (
                '/img/'.self::getRequestedSessionId()."/$fileName"
            );
        }

        foreach ($images as $zoomNum => $zoom) {
            echo 'zoom: ' . $zoomNum . '<br>';
            ksort($zoom);
            foreach ($zoom as $pageNum => $page) {
                echo 'page: ' . $pageNum . '<br>';
                ksort($page);
                echo "<div>";
                foreach ($page as $row) {
                    ksort($row);
                    foreach ($row as $col) {
                        echo "<img src=\"$col\" width=\"" . (25 * $zoomNum) . "px\" border=\"1px\">";
                    }
                    unset($cols);
                    echo "<br>";
                }
                unset($row);
                echo "</div>";
            }
            unset($page);
            echo "<hr>";
        }
        unset($zoom);
    }
}
