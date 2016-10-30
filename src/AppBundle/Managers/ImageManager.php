<?php


namespace AppBundle\Managers;


use Intervention\Image\Image;
use Intervention\Image\ImageManager as InterventionImage;

class ImageManager
{
    protected $im;
    /** @var Image $ image */
    public $image;
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

    public function make( $image )
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
    public function createAvatar( int $width = 100, int $height = 100 )
    {
        $this->image->resize( $width, $height )->save( $this->file );

        return $this;
    }

    /**
     * Cree un slide 100x100
     *
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function createSlide( int $width = 100, int $height = 100 )
    {
        $this->image->resize( $width, $height )->save( $this->file );

        return $this;
    }
}
