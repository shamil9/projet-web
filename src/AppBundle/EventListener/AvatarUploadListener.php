<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\User;
use AppBundle\Managers\ImageManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarUploadListener
{
    private $imageManager;

    public function __construct(ImageManager $uploader)
    {
        $this->imageManager = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        /** @var User $entity */
        $entity = $args->getEntity();

        unlink(__DIR__ . '/../../../web/assets/img/uploads/avatars/' . $entity->getPicture());
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof ProMember || !$entity instanceof Member) {
            return;
        }

        $file = $entity->getPicture();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->imageManager->make($file);
        $entity->setPicture($fileName);
    }
}
