<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CatTableRepository;
use App\Repository\ForumTableRepository;
use App\Entity\CatTable;
use App\Entity\ForumTable;
use App\Entity\ThreadTable;
use App\Entity\PostTable;
use App\Form\PostType;

class ForumController extends AbstractController
{
    /**
     *  @Route("/forum" , name="view_forums_and_categorys")
     */
    public function viewForumsAndCategorys(CatTableRepository $repo1, ForumTableRepository $repo2)
    {
        $categories = $repo1->findAll();
        $forums = $repo2->findAll();

        return $this->render('forum/view_forums_and_categorys.html.twig', [
            'categories' => $categories ,
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/forum/{CatName}/{CatId}/{id}", name="view_forum")
     */
    public function viewForum(ForumTable $ForumTable)
    {
        if(empty($ForumTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        return $this->render('forum/view_forum.html.twig',[
            'forum'=> $ForumTable
        ]);
    }

    /**
     * @Route("/forum/{CatName}/{CatId}/{ForumId}/view_topic_{id}", name="view_thread")
     */
    public function viewThread(ThreadTable $ThreadTable, Request $request, ObjectManager $manager, $CatName, $CatId, $ForumId)
    {
        if(empty($ThreadTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if($this->getUser()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            $PostTable = new PostTable();
            $form = $this->createForm(PostType::class, $PostTable);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $PostTable ->setPostAuthor($user)
                    ->setPostTime(new \DateTime())
                    ->setThread($ThreadTable);
                $manager->persist($PostTable);
                $manager->flush();
                return $this->redirectToRoute('view_thread', [
                    'CatName' => $CatName,
                    'CatId' => $CatId,
                    'ForumId' => $ForumId,
                    'id' => $ThreadTable->getId()]
                );
            }
            return $this->render('forum/view_thread.html.twig', [
                'thread' => $ThreadTable,
                'PostForm' => $form->createView()
            ]);
        }
        else if(!$this->getUser()) {
            return $this->render('forum/view_thread.html.twig', [
                'thread' => $ThreadTable
            ]);
        }
    }
}