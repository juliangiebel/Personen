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


        return $this->render('default/index.html.twig', $userList);
    }

    /**
     * @Route("/show/{slug}", name="show")
     */
    public function showAction($slug){

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(AppUser::class)
            ->getById($slug);

        return $this->render('', $user);
    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function editAction($slug, Request $request){

        //TODO: Implement edit
        if(false){
            //return
        }

        return $this->redirectToRoute($this->generateUrl('show',$slug));
    }

    /**
     * @Route("/edit/{slug}", name="delete")
     */
    public function deleteAction($slug){

        return $this->redirectToRoute("homepage");
    }



}
