<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ForumTable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\CategoryType;
use App\Form\ForumType;
use App\Entity\CatTable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/forum/supprimer_forum_{id}", name="remove_forum")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function dellForum(ForumTable $ForumTable, ObjectManager $manager)
    {
        if(empty($ForumTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        $manager->remove($ForumTable);
        $manager->flush();
        return $this->redirectToRoute('view_forums_and_categorys');
    }

    /**
     * @Route("/forum/supprimer_categorie_{id}", name="remove_category")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function dellCategory(CatTable $CatTable, ObjectManager $manager)
    {
        if(empty($CatTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        $manager->remove($CatTable);
        $manager->flush();
        return $this->redirectToRoute('view_forums_and_categorys');
    }

    /**
     * @Route("/forum/new_category" , name="new_category")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newCategory(Request $request, ObjectManager $manager)
    {
        $CatTable = new CatTable();
        $form = $this->createForm(CategoryType::class, $CatTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($CatTable);
            $manager->flush();
            return $this->redirectToRoute('view_forums_and_categorys');
        }
        return $this->render('SuperAdmin/new_category.html.twig', [
            'CatForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forum/edit_category_{id}", name="edit_category")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editCategory(CatTable $CatTable, Request $request, ObjectManager $manager)
    {
        if(empty($CatTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        $form = $this->createForm(CategoryType::class, $CatTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($CatTable);
            $manager->flush();
            return $this->redirectToRoute('view_forums_and_categorys');
        }
        return $this->render('SuperAdmin/edit_category.html.twig', [
            'CatForm' => $form->createView(),
            'Categorie' => $CatTable
        ]);
    }
}