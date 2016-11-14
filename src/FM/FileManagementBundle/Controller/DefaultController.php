<?php

namespace FM\FileManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$fileM = $this->get('fm_file_management.filem');
        return $this->render('FMFileManagementBundle:Default:index.html.twig');
    }
}
