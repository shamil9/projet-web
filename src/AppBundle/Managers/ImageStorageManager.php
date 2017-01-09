<?php

namespace AppBundle\Managers;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* Enregistrement des images soumises depuis les différents formulaire du site
*/
class ImageStorageManager
{
    private $avatarsFolder;
    private $slidersFolder;

    public function __construct(string $avatars, string $sliders, string $categories)
    {
        $this->avatarsFolder = $avatars;
        $this->slidersFolder = $sliders;
        $this->categoriesFolder = $categories;
    }

    /**
     * Déplace et enregistre l'image du slider
     * @param  Image  $slide
     * @return string
     */
    public function storeSliderImage(Image $slide)
    {
        /** @var UploadedFile $file */
        $file = $slide->getPath();
        $fileName = random_int(0, 99999) . '.' . $file->guessExtension();

        $file->move($this->slidersFolder, $fileName);

        return $this->slidersFolder . $fileName;
    }

    /**
     * Déplace et enregistre l'image d'avatar
     *
     * @param $user User
     * @return string
     */
    public function storeAvatarImage(User $user)
    {
        /** @var UploadedFile $file */
        $file = $user->getPicture();
        $fileName = $user->getUsername() . '.' . $file->guessExtension();

        $file->move($this->avatarsFolder, $fileName);

        return $this->avatarsFolder . $fileName;
    }

    public function storeCategoryImage(Category $category)
    {
        /** @var UploadedFile $file */
        $file = $category->getImage();
        $fileName = random_int(0, 99999) . '.' . $file->guessExtension();

        $file->move($this->categoriesFolder, $fileName);

        return $this->categoriesFolder . $fileName;
    }
}
