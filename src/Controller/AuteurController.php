<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AddArticleType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auteur')]
class AuteurController extends AbstractController
{
    #[Route('/add', name: 'app_addarticle')]
    public function add(Request $request,EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // On envoie le formulaire pour écrire un article
        $article = new Article();
        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            //on recupere le submit
            $uploadedFile = $form->get('imageFile')->getData();;

            //Ajout de son nom dans la BDD et de son path
            if ($uploadedFile) {

                $uploadedFileName = $fileUploader->upload($uploadedFile);
                $article->setImageFile('/uploads/' . $uploadedFileName);
                $article->setCreationDate(new \DateTime());
                $article->setIdUser($this->getUser());
                $article->setDeleted(0);
                $entityManager->persist($article);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_home');
        }
        return $this->renderForm('admin/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id,EntityManagerInterface $entityManager): Response
    {

        $articles = $entityManager
            ->getRepository(Article::class)
            ->find($id);

        $articles->setDeleted(1);
        $entityManager->persist($articles);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/update/{id}', name: 'app_update')]
    public function update($id, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // On recup les données de l'article
        $article = $entityManager
            ->getRepository(Article::class)
            ->find($id);

        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            //on recupere le submit
            $uploadedFile = $form->get('imageFile')->getData();

            //Si image est pas donné, garde la précedente
            if ($uploadedFile != null) {
                $image = $article->getImageFile();
                $article->setImageFile($image);
            }

            //Ajout de son nom dans la BDD et du path de l'image si une image a été mise
            if ($uploadedFile) {
                $uploadedFileName = $fileUploader->upload($uploadedFile);
                $article->setImageFile('/uploads/' . $uploadedFileName);
            }
            $article->setCreationDate(new \DateTime());
            $article->setIdUser($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->renderForm('admin/update.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
    }

}