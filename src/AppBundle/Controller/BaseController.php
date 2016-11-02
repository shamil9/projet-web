<?php


namespace AppBundle\Controller;


use Illuminate\Support\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $repo
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($repo)
    {
        return $this->getDoctrine()->getRepository($repo);
    }

    /**
     * Retourne une instance de Laravel Collection
     *
     * @param $array
     * @return Collection
     */
    protected function collection($array)
    {
        return new Collection($array);
    }

    /**
     * @param Request $request
     * @param $user
     */
    protected function deleteUser( Request $request, $user ):void
    {
        //suppression d'utilistateur
        $this->em()->remove( $user );
        $this->em()->flush();

        //suppression de la session
        $this->get( 'security.token_storage' )->setToken( null );
        $request->getSession()->invalidate();
    }


    /**
     * @param string $file
     */
    protected function createAvatarImage( string $file )
    {
        $image = $this->get( 'app.image_manager' )->make( $file );
        $image->createAvatar();
    }

    /**
     * @param string $file
     */
    protected function createSliderImage( string $file )
    {
        $image = $this->get( 'app.image_manager' )->make( $file );
        $image->createSlide();
    }

    protected function userCheck()
    {
        if ( !$this->getUser() ) {
            $this->createAccessDeniedException( 'Action non autorisée' );
        }
    }
}
