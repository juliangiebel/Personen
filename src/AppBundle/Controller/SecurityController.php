<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 22.05.18
 * Time: 16:37
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request){
        $authUtils = $this->get('security.authentication_utils');

        $error = $authUtils->getLastAuthenticationError();

        $lastEmail = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
           'last_email' => $lastEmail,
            'error'     => $error
        ));
    }
}