<?php


namespace AppBundle\Managers;

use Intervention\Image\Image;
use Intervention\Image\ImageManager as InterventionImage;

class ImageManager
{
    /** @var Image $ image */
    public $image;
    protected $im;
    private $file;
    private $assets;

    /**
     * ImageManager constructor.
     * @param string $assets
     */
    public function __construct(string $assets)
    {
        $this->im = new InterventionImage(['driver' => 'gd']);
        $this->assets = $assets;
    }

    public function make($image)
    {
        $this->file = $image;
        $this->image = $this->im->make($image);

        return $this;
    }

    /**
     * Cree un avatar 100x100
     *
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function createAvatar(int $width = 100, int $height = 100)
    {
        return $this->image->resize($width, $height)->save($this->file);
    }

    /**
     * Cree un slide
     *
     * @param int $width
     * @return $this
     */
    public function createSlide(int $width = 1140, int $height = 445)
    {
        return $this->image
            ->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($this->file);
    }

    public function createCategoryImage(int $width = 360, int $height = 245)
    {
        return $this->image->resize($width, $height)->save($this->file);
    }
}
