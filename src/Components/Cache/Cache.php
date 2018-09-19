<?php

namespace Nur\Components\Cache;

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
     * @param int    $time
     *
     * @return string|bool
     */
    public function save($content = null, $time = 30)
    {
        $fileName = md5($this->prefix . http()->server('REQUEST_URI')) . $this->extension;
        $this->file = cache_path('html' . DIRECTORY_SEPARATOR . $fileName);

        $this->start($time);
        return $this->finish($content);
    }

    /**
     * Cache start
     *
     * @param int $time
     *
     * @return mixed
     */
    protected function start($time = 1)
    {
        if (file_exists($this->file)) {
            if (time() - $time < filemtime($this->file)) {
                die(readfile($this->file));
            }
            return $this->delete();
        }

        touch($this->file);
    }

    /**
     * Finish cache and save file
     *
     * @param string $output
     *
     * @return mixed
     */
    protected function finish($output = null)
    {
        if (! is_null($output)) {
            $file = fopen($this->file, 'w+');
            fwrite($file, $output);
            fclose($file);
            return $output;
        }

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
