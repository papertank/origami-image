<?php namespace Origami\Image;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use \Intervention\Image\ImageManagerStatic as Intervention;
use Origami\Image\Exceptions\FilePermissionException;
use Origami\Image\Exceptions\NotFoundException;

class Image {

    /**
     * @var string
     */
    private $path;

    /**
     * @var \Intervention\Image\Image
     */
    protected $manipulateInstance;

    public function __construct($path)
    {
        $this->path = $path;

        if ( ! File::exists($path) ) {
            throw new NotFoundException;
        }
    }

    public function url($secure = null)
    {
        return asset($this->getFolder().'/'.$this->getFilename(), $secure);
    }

    public function sizesUrl($size, $secure = null)
    {
        return asset($this->getSizesFolder($size).'/'.$this->getFilename(), $secure);
    }

    public function getFilename()
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    public function getFolder()
    {
        return substr(pathinfo($this->path, PATHINFO_DIRNAME),strlen(public_path())+1);
    }

    public function getDirname()
    {
        return pathinfo($this->path, PATHINFO_DIRNAME);
    }

    public function getSizesPath($size)
    {
        $folder = '/sizes/'.$size;

        return $this->getDirname().$folder;
    }

    public function getOrMakeSizesPath($size)
    {
        $path = $this->getSizesPath($size);

        if ( ! File::isDirectory($path) &&
            ! File::makeDirectory($path, 0777, true) )
        {
            throw new FilePermissionException('Unable to create '.$this->getSizesFolder($size));
        }

        return $path;
    }

    public function getSizesFolder($size)
    {
        $folder = '/sizes/'.$size;

        return $this->getFolder().$folder;
    }

    public function resized($size = 'original', $secure = null)
    {
        $folder = $this->getSizesFolder($size);
        $path = $this->getOrMakeSizesPath($size).'/'.$this->getFilename();

        if ( ! File::exists($path) ) {

            $callback = Config::get('origami/image::templates.'.$size);

            if ( is_callable($callback) ) {

                $callback($this->manipulate())
                        ->save($path, 100);

            } else {
                File::copy($this->path, $path);
            }

        }

        return $this->sizesUrl($size, $secure);
    }

    /**
     * Prepare a new or cached manipulator instance
     *
     * @return mixed
     */
    public function manipulate()
    {
        if ( ! $this->manipulateInstance)
        {
            $this->manipulateInstance = Intervention::make($this->path);
        }

        return $this->manipulateInstance;
    }

}