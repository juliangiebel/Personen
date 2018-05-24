<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//TODO: Require slug to be a string
class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(){

        $entityManager = $this->getDoctrine()->getManager();
        $userList = $entityManager->getRepository(AppUser::class)
            ->getAllOrderedBy('name');


        return $this->render('default/index.html.twig', array('users'=>$userList));
    }

    /**
     * @Route("/show/{slug}", name="show")
     */
    public function showAction($slug){

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(AppUser::class)
            ->getByEmail($slug);

        return $this->render('default/show.html.twig', $user);

    }


}
