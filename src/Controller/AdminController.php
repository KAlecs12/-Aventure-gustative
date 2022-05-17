<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function new(): Response
    {
        return $this->renderForm('admin/admin.html.twig');
    }

}
