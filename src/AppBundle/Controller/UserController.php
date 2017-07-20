<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/", name="app_user_login")
     */
	public function loginAction()
    {
        return $this->render('AppBundle:User:login.html.twig');
    }
    
        /**
     * @Route("/create-account", name="app_user_register")
     */
	public function registerAction()
    {
        return $this->forward('FOSUserBundle:Registration:register');
        //return $this->render('AppBundle:User:register.html.twig');
    }
    

}