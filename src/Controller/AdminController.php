<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ForumTable;
use App\Form\ForumType;

class AdminController extends AbstractController
{
    //////////////////////////////////////////////////AJOUTER UN FORUM AUTHOR///////////////////////////////////////////

    /**
     * @Route("/forum/new_forum" , name="new_forum")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newForum(Request $request, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $ForumTable = new ForumTable();
        $form = $this->createForm(ForumType::class, $ForumTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $ForumTable->setForumAuthor($user);
            $manager->persist($ForumTable);
            $manager->flush();
            return $this->redirectToRoute('view_forums_and_categorys');
        }
        return $this->render('Admin/new_forum.html.twig', [
            'ForumForm' => $form->createView(),
            'Forum' => $ForumTable
        ]);
    }


    /**
     * @Route("forum/edit_forum_{id}", name="edit_forum")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editForum(ForumTable $ForumTable, Request $request, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(empty($ForumTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if($this->isGranted('ROLE_SUPER_ADMIN') || $user->getId() == $ForumTable->getForumAuthor()->getId() )
        {
            $form = $this->createForm(ForumType::class, $ForumTable);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $ForumTable->setForumAuthor($user);
                $manager->persist($ForumTable);
                $manager->flush();
                return $this->redirectToRoute('forum');
            }
            return $this->render('Admin/edit_forum.html.twig', [
                'ForumForm' => $form->createView(),
                'Forum' => $ForumTable
            ]);
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }
}