<?php


namespace AppBundle\Form;

use AppBundle\Entity\Member;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class MemberForm implements FormInterface
{
    use ContainerAwareTrait;

    private $user;
    private $request;

    /**
     * MemberForm constructor.
     * @param Member $user
     * @param Request $request
     * @internal param $ObjectManager
     */
    public function __construct(Member $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function process()
    {
        $this->saveAvatar();
    }

    public function saveAvatar()
    {
        if ($this->request->files->get('member_edit')['picture']) {
            /** @var UploadedFile $file */
            $file = $this->request->files->get('member_edit')['picture'];

            $fileName = $this->user->getUsername() . '.' . $file->guessExtension();
            $folder = $this->container->getParameter('assets_root') . '/img/uploads/avatars/';

            $file->move($folder, $fileName);

            $this->container->get('app.image_manager')->make($folder . $fileName)->createAvatar();
        } else {
            dump($this->request->files);
            die();
        }
    }

    public function save(ObjectManager $em)
    {
        $em->persist($this->user);
        $em->flush();
    }
}
