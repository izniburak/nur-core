<?php

namespace Nur\Components\Cache;

use Nur\Facades\Http;

class Cache
{
    /**
     * Cache file target
     * 
     * @var string
     */
    protected $file;

    /**
     * Cache save path 
     * 
     * @var string
     */
    protected $path = '/storage/cache/html';

    /**
     * Cache file prefix
     * 
     * @var string
     */
    protected $prefix = '%nurfw%';

    /**
     * Cache file extension 
     * 
     * @var string
     */
    protected $extension = '.cache';

    /**
     * Save cache file
     * 
     * @param string $content
     * $param int $time 
     * @return string|bool
     */
    public function save($content = null, $time = 30)
    {
        $fileName = '/' . md5($this->prefix . Http::server('REQUEST_URI')) . $this->extension;
        $this->file = ROOT . $this->path . $fileName;

        $this->start($time);
        return $this->finish($content);
    }
    /**
     * Cache start 
     * 
     * @param int $time 
     * @return void 
     */
    protected function start($time = 1)
    {
        if (file_exists($this->file)) {
            if (time() - $time < filemtime($this->file)) {
                die(readfile($this->file));
            }
            else {
                return $this->delete();
            }
        }
        else {
            touch($this->file);
        }
    }

    /**
     * Finish cache and save file 
     * 
     * @param string $output 
     * @return void
     */
    protected function finish($output = null)
    {
        if(!is_null($output)) {
            $file = fopen($this->file, 'w+');
            fwrite($file, $output);
            fclose($file);
            return $output;
        }
        else 
            return false;
    }

    /**
     * Delete cache file
     * 
     * @return bool
     */
    protected function delete()
    {
        return unlink($this->file);
    }
}
