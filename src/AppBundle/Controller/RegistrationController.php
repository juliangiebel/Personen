<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 23.05.18
 * Time: 11:20
 */

namespace AppBundle\Controller;

use AppBundle\Form\AppUserType;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request){

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
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

}