<?php namespace Origami\Image;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use \Intervention\Image\ImageManagerStatic as Intervention;
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
        return asset(Config::get('origami/image::url').'/'.$this->getFilename(), $secure);
    }

    public function getFilename()
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    public function resized($size = 'original', $secure = null)
    {
        return url(Config::get('origami/image::sizes_route').'/'.$size.'/'.$this->getFilename(), $secure);
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