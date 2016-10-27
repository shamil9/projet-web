<?php


namespace AppBundle\Managers;


use Intervention\Image\ImageManager as InterventionImage;

class ImageManager
{
    protected $image;
    private $assets;

    /**
     * ImageManager constructor.
     * @param string $assets
     */
    public function __construct(string $assets)
    {
        $this->image = new InterventionImage(['driver' => 'gd']);
        $this->assets = $assets;
    }

    public function make( $file )
    {
        return $this->image->make($file);
    }
}
