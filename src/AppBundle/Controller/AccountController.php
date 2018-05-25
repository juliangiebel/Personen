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
use Symfony\Component\HttpFoundation\Session\Session;


class AccountController extends Controller
{

    /**
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

            //TODO: Handle file upload using either a service or using a Doctrine listener
            //Get picture file

            $file = $user->getPictureUrl();

            $filename = $this->genUniqueFilename().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('user_pictures_dir'),
                $filename
            );

            $user->setPictureUrl($filename);

            //Default role
            //TODO: Put this into some sort of config file
            $user->setRoles(array('ROLE_USER'));

            //Set user as active
            $user->setActive(true);

            //Persist to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //TODO: Add success notification after redirecting.

            return $this->redirectToRoute('login');

        }

        return $this->render(
            'account/register.html.twig',
            array('form' => $form->createView())
        );


    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function editAction($slug, Request $request){

        $userEmail = $this->getUser()->getEmail();

        if($userEmail !== $slug) {
            return $this->redirectToRoute('show', array('slug' => $slug));
        }

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

            $entityManager->merge($user);
            $entityManager->flush();

            //TODO: Add success notification after redirecting.
            return $this->redirectToRoute('show',array('slug'=> $slug));


        }

        return $this->render('account/edit.html.twig', array(
            'form' => $form->createView()
        ));



    }

    /**
     * @Route("/delete/{slug}", name="delete")
     */
    public function deleteAction(Request $request,$slug){

        //TODO: Implement deleting entries (low priority)
        //TODO: Implement confirmation prompt


        $userEmail = $this->getUser()->getEmail();

        if($userEmail === $slug) {
            $entityManager = $this->getDoctrine()->getManager();

            $userArray = $entityManager->getRepository(AppUser::class)->getByEmail($slug);

            $user = $entityManager->getRepository(AppUser::class)->find($userArray[0]['id']);

            $entityManager->remove($user);

            $entityManager->flush();

            $this->get('security.token_storage')->setToken(null);
            $this->get('request')->getSession()->invalidate();

        }

        return $this->redirectToRoute("homepage");
    }

    private function genUniqueFilename(){
        return md5(uniqid());
    }

}