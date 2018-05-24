<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 23.05.18
 * Time: 11:20
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Form\AppUserType;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AccountController extends Controller
{

    /**@TODO: Document
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request){

        //TODO: Check if account email is already in use
        $user = new AppUser();
        $form = $this->createForm(AppUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            //Password hash
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPwdhash($password);
            //Default role
            //TODO: Put this into some sort of config file
            $user->setRoles(array('ROLE_USER'));

            $user->setActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //TODO: Add success notification after redirecting.

            return $this->redirectToRoute('homepage');

        }

        return $this->render(
            'account/register.html.twig',
            array('form' => $form->createView())
        );


    }

    /**@TODO: Document
     * @Route("/edit/{slug}", name="edit")
     */
    public function editAction($slug, Request $request){

        $userEmail = $this->getUser()->getEmail();

        if($userEmail === $slug){

            $entityManager = $this->getDoctrine()->getManager();
            //0:{Id|Title|Name|Surname|email|PostCode|City|Street}
            $userArray = $entityManager->getRepository(AppUser::class)
                ->getByEmail($userEmail);

            //Creates user object from array
            $user = new AppUser();

            $user->fromArray($userArray[0]);

            $form = $this->createForm(AppUserType::class, $user, array( 'method' => 'PUT' ))
                ->remove('email');

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->merge($user);
                $entityManager->flush();

                //TODO: Add success notification after redirecting.
                return $this->redirectToRoute('show',array('slug'=> $slug));


            }

            return $this->render('account/edit.html.twig', array(
                'form' => $form->createView()
            ));
        }

        return $this->redirectToRoute('show',array('slug'=> $slug));
    }

    /**@TODO: Document
     * @Route("/delete/{slug}", name="delete")
     */
    public function deleteAction($slug){

        //TODO: Implement deleting entries (low priority)
        //TODO: Implement confirmation prompt

        return $this->redirectToRoute("homepage");
    }

}