<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\AddArticleType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager
            ->getRepository(Article::class)
            ->findBy(array('deleted' => 0));


        return $this->renderForm('admin/admin.html.twig', [
            'articles' => $articles
        ]);
    }


    #[Route('/contact', name: 'app_admincontact')]
    public function admincontact(Request $request,EntityManagerInterface $entityManager): Response
    {
        $contacts = $entityManager
            ->getRepository(Contact::class)
            ->findBy(array('deleted' => 0));


        return $this->renderForm('contact/admincontact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/promote/{id}', name: 'app_promote')]
    public function promote($id, Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

        $user->setRoles(['ROLE_AUTEUR']);

        $entityManager->persist($user);
        $entityManager->flush();

         return $this->redirectToRoute('app_admin');
    }
}
