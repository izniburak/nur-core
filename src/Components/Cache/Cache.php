<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Components\Cache;

use Nur\Http\Http;

class Cache
{
    private static $file;

    public function __construct()
    {
        $fileName = md5('%nurfw%-' . Http::server('REQUEST_URI')) . '.cache';
        $path = realpath(ROOT . '/storage/cache/html/');

        if (!file_exists($path))
            $creatPath = mkdir($path, 0777);
        self::$file = $path . $fileName;
        if(!file_exists(self::$file))
            touch(self::$file);
    }

    public static function start($time = 1)
    {
        if (file_exists(self::$file))
        {
            if (time() - $time < filemtime(self::$file))
            {
                readfile(self::$file);
                die();
            }
            else
                self::delete();
        }
        return;
    }

    public static function finish($output = null)
    {
        $file = fopen(self::$file, 'w+');
        fwrite($file, (is_null($output) ? ob_get_contents() : $output));
        fclose($file);
        return;
    }

    protected static function delete()
    {
        unlink(self::$file);
        return;
    }
}
