<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ThreadTable;
use App\Entity\PostTable;
use App\Entity\ForumTable;
use App\Entity\PrivateMessageTable;
use App\Entity\User;
use App\Entity\ReportTable;
use App\Entity\Avatar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Form\PostType;
use App\Form\EditThreadType;
use App\Form\ModoEditThreadType;
use App\Form\NewThreadType;
use App\Form\SendPrivateMessageType;
use App\Form\ReportType;
use App\Form\ProfileImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\UserRepository;
use App\Repository\PrivateMessageTableRepository;
use App\Repository\AvatarRepository;

class UserController extends AbstractController
{


    /**
     * @Route("/forum/{CatName}/{CatId}/{id}/new_thread", name="new_thread")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function NewThreadForm(ForumTable $ForumTable, Request $request, ObjectManager $manager, $CatName ,$CatId)
    {
        if(empty($ForumTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        $ThreadTable = new ThreadTable();
        $form = $this->createForm(NewThreadType::class, $ThreadTable);
        $form->handleRequest($request);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            $ThreadTable->setThreadAuthor($user)
                        ->setThreadTime(new \DateTime())
                        ->setForum($ForumTable);
            $manager->persist($ThreadTable);
            $manager->flush();
            return $this->redirectToRoute('view_thread', ['CatName' => $CatName, 'CatId' => $CatId, 'ForumId' => $ThreadTable->getForum()->getId(), 'id' => $ThreadTable->getId() ]);
        }
        return $this->render('User/new_thread.html.twig', [
            'NewThreadForm' => $form->createView(),
            'Forum' => $ForumTable
        ]);
    }

    //////////////////////////////////////////////////////////////////SUPRESSION DE CONTENU//////////////////////////////////////////////////////

    /**
     * @Route("/forum/{CatName}/{CatId}/{ForumId}/supprimer_thread{id}", name="remove_thread")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function removeThread(ThreadTable $ThreadTable, ObjectManager $manager, $CatName, $CatId, $ForumId)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(empty($ThreadTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if( $this->isGranted('ROLE_ADMIN') || $user->getId() == $ThreadTable->getThreadAuthor()->getId()) {
            $manager->remove($ThreadTable);
            $manager->flush();

            return $this->redirectToRoute('view_forum', ['CatName' => $CatName, 'CatId' => $CatId, 'id' => $ForumId]);
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }

    /**
     * @Route("/forum/{CatName}/{CatId}_{ForumId}/{ThreadId}/supprimer_post{id}", name="remove_post")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function removePost(PostTable $PostTable,ObjectManager $manager, $CatName, $CatId, $ForumId, $ThreadId)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(empty($PostTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if( $this->isGranted('ROLE_MODERATOR') || $user->getId() == $PostTable->getPostAuthor()->getId()) {
            $manager->remove($PostTable);
            $manager->flush();

            return $this->redirectToRoute('view_thread', ['CatName' => $CatName, 'CatId' => $CatId, 'ForumId' => $ForumId, 'id' => $ThreadId ]);
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }

    //////////////////////////////////////////EDITER UN POST/////////////////////////////////////////////////////////////////////
    /**
     * @Route("/forum/{CatName}/{CatId}_{ForumId}/{ThreadId}/{id}_edit", name="edit_post")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function post_edit(PostTable $PostTable, Request $request, ObjectManager $manager, $CatName, $CatId, $ForumId)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(empty($PostTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if( $this->isGranted('ROLE_MODERATOR') || $user->getId() == $PostTable->getPostAuthor()->getId()) {
            $form = $this->createForm(PostType::class, $PostTable);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $manager->persist($PostTable);
                $manager->flush();
                return $this->redirectToRoute('view_thread', ['CatName' => $CatName, 'CatId' => $CatId, 'ForumId' => $ForumId, 'id' => $PostTable->getThread()->getId() ]);
            }
            return $this->render('User/edit_post.html.twig', [
                'PostForm' => $form->createView(),
                'Post' => $PostTable
            ]);
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }

    /**
     * @Route("/forum/{CatName}/{CatId}_{ForumId}/{id}/edit_thread", name="edit_thread")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function EditThreadForm(ThreadTable $ThreadTable, Request $request, ObjectManager $manager, $CatName, $CatId, $ForumId)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(empty($ThreadTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if($this->isGranted('ROLE_ADMIN') || $user->getId() == $ThreadTable->getThreadAuthor()->getId()) {
            $form = $this->createForm(EditThreadType::class, $ThreadTable);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                    $manager->persist($ThreadTable);
                    $manager->flush();
                    return $this->redirectToRoute('view_thread', ['CatName' => $CatName, 'CatId' => $CatId, 'ForumId' => $ForumId, 'id' => $ThreadTable->getId() ]);
            }
            return $this->render('User/edit_thread.html.twig', [
                'EditThreadForm' => $form->createView(),
                'Thread' => $ThreadTable,
            ]);
        }
        else if($user->getRoles() == ['ROLE_MODERATOR']) {
            $form = $this->createForm(ModoEditThreadType::class, $ThreadTable);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                    $manager->persist($ThreadTable);
                    $manager->flush();
                    return $this->redirectToRoute('view_thread', ['CatName' => $CatName, 'CatId' => $CatId, 'ForumId' => $ForumId, 'id' => $ThreadTable->getId() ]);
            }
            return $this->render('User/edit_thread.html.twig', [
                'EditThreadForm' => $form->createView(),
                'Thread' => $ThreadTable,
            ]);
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }

    ////////////////////////////ROUTE MEMBRES DU FORUM/Fonctions Membres//////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/membres", name="member_list")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function user(UserRepository $repo)
    {
        $users = $repo->findAllSortByUsername();
        return $this->render('User/UserList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/membres/profil/membre_{id}", name="member_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function userProfile(User $userProfile)
    {
        /** @var \App\Entity\User $userConnected */
        $user = $this->getUser();

        if(empty($userProfile)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if ($user->getId() !== $userProfile->getId()){
            return $this->render('User/UserProfile.html.twig', [
                'userProfile' => $userProfile
            ]);
        }
        else if ($user->getId() == $userProfile->getId()){
            return $this->redirectToRoute('member_my_profile');
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
    }

    /**
     * @Route("/membres/profil/mon_profile", name="member_my_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function userMyProfile(Request $request, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();


        $avatar = new Avatar();

        $form = $this->createForm(ProfileImageType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $file stores the uploaded png or jpeg file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $avatar->getAvatarImg();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('avatar_image_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $avatar->setAvatarImg($fileName);
            $user->setAvatar($avatar);

            $manager->persist($avatar);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('member_my_profile');
        }

        return $this->render('User/UserMyProfile.html.twig', [
            'user' => $user,
            'formAvatarImg' => $form->createView()
            ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    ///////////////////////////////////////////////////////////SIGNALEMENT ///////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/membres/signaler/post_{id}", name="report_post")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function reportPost(PostTable $PostTable, Request $request, ObjectManager $manager)
    {
        if(empty($PostTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $ReportTable = new ReportTable();
        $form = $this->createForm(ReportType::class, $ReportTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $AddReport = $PostTable->getReport() + 1;
            $PostTable->setReport($AddReport);
            $ReportTable->setReportAuthor($user)
                ->setReportPost($PostTable);
            $manager->persist($PostTable);
            $manager->persist($ReportTable);
            $manager->flush();
            return $this->redirectToRoute('view_thread', [
                'CatName' => $PostTable->getThread()->getForum()->getCategory()->getCatName(),
                'CatId' => $PostTable->getThread()->getForum()->getCategory()->getId(),
                'ForumId' => $PostTable->getThread()->getForum()->getId(),
                'id' => $PostTable->getThread()->getId()]
            );
        }
        return $this->render('User/report.html.twig', [
            'formReport' => $form->createView()
        ]);

    }

    /**
     * @Route("/membres/signaler/thread_{id}", name="report_thread")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function reportThread(ThreadTable $ThreadTable, Request $request, ObjectManager $manager)
    {
        if(empty($ThreadTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $ReportTable = new ReportTable();
        $form = $this->createForm(ReportType::class, $ReportTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $AddReport = $ThreadTable->getReport() + 1;
            $ThreadTable->setReport($AddReport);
            $ReportTable->setReportAuthor($user)
                ->setReportThread($ThreadTable);
            $manager->persist($ThreadTable);
            $manager->persist($ReportTable);
            $manager->flush();
            return $this->redirectToRoute('view_thread', [
                'CatName' => $ThreadTable->getForum()->getCategory()->getCatName(),
                'CatId' => $ThreadTable->getForum()->getCategory()->getId(),
                'ForumId' => $ThreadTable->getForum()->getId(),
                'id' => $ThreadTable->getId()]
            );
        }
        return $this->render('User/report.html.twig', [
            'formReport' => $form->createView()
        ]);

    }

    /**
     * @Route("/membres/signaler/utilisateur_{id}", name="report_user")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function reportUser(User $userReported, Request $request, ObjectManager $manager)
    {
        if(empty($userReported)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $ReportTable = new ReportTable();
        $form = $this->createForm(ReportType::class, $ReportTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $AddReport = $userReported->getReport() + 1;
            $userReported->setReport($AddReport);
            $ReportTable->setReportAuthor($user)
                ->setReportUser($userReported);
            $manager->persist($userReported);
            $manager->persist($ReportTable);
            $manager->flush();
            return $this->redirectToRoute('member_profile', [
                'id' => $userReported->getId()]
            );
        }
        return $this->render('User/report.html.twig', [
            'formReport' => $form->createView()
        ]);

    }


    //////////////////////////////////////////////////////////////////////////FONCTION MESSAGERIE///////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/messagerie", name="private_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function privateMessages(Request $request, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $Messages = $this->getDoctrine()->getRepository(PrivateMessageTable::class);
        $ReceptionBox = $Messages->findMessageReceive($user->getId());
        $SendingBox = $Messages->findMessageSend($user->getId());
        $ArchivedBoxReceive = $Messages->findArchivedMessageReceive($user->getId());
        $ArchivedBoxSend = $Messages->findArchivedMessageSend($user->getId());
        $DeletedBoxReceive = $Messages->findDeletedMessageReceive($user->getId());
        $DeletedBoxSend = $Messages->findDeletedMessageSend($user->getId());
        $AlertUsername = false ;
        $PrivateMessageTable = new PrivateMessageTable();
        $form = $this->createForm(SendPrivateMessageType::class, $PrivateMessageTable);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $userReceiver = $this->getDoctrine()->getRepository(User::class)->findOneByUsername($form->get('ReceiverUsername')->getData());
            if(empty($userReceiver)) {
                $AlertUsername = true;
            }
            else {
                $PrivateMessageTable->setMessageAuthor($user)
                    ->setMessageReceiver($userReceiver)
                    ->setMessageTime(new \DateTime());
                $manager->persist($PrivateMessageTable);
                $manager->flush();
                return $this->redirectToRoute('private_message');
            }
        }
        return $this->render('User/private_message.html.twig', [
            'ReceptionBox' => $ReceptionBox,
            'SendingBox' => $SendingBox,
            'ArchivedBoxReceive' => $ArchivedBoxReceive,
            'ArchivedBoxSend' => $ArchivedBoxSend,
            'DeletedBoxReceive' => $DeletedBoxReceive,
            'DeletedBoxSend'    => $DeletedBoxSend,
            'SendPrivateMessageForm' => $form->createView(),
            'AlertUsername' => $AlertUsername
        ]);
    }

    /**
     * @Route("/messagerie/envoyer_message/{id}", name="private_message_send_to")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function sendMessageTo(User $userToSend, Request $request, ObjectManager $manager)
    {
        $usernameToSend = $userToSend->getUsername();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $AlertUsername = false ;
        $PrivateMessageTable = new PrivateMessageTable();
        $form = $this->createForm(SendPrivateMessageType::class, $PrivateMessageTable);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userReceiver = $this->getDoctrine()->getRepository(User::class)->findOneByUsername($form->get('ReceiverUsername')->getData());
            if(empty($userReceiver)) {
                $AlertUsername = true;
            }
            else {
                $PrivateMessageTable->setMessageAuthor($user)
                    ->setMessageReceiver($userReceiver)
                    ->setMessageTime(new \DateTime());
                $manager->persist($PrivateMessageTable);
                $manager->flush();
                return $this->redirectToRoute('private_message');
            }
        }

        return $this->render('User/private_message_send_to.html.twig', [
            'usernameToSend' => $usernameToSend,
            'SendPrivateMessageForm' => $form->createView(),
            'AlertUsername' => $AlertUsername
        ]);
    }

    /**
     * @Route("/messagerie/archive_message_{id}", name="private_message_archived")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function archivedPrivateMessage(PrivateMessageTable $PrivateMessageTable, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if(empty($PrivateMessageTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if($PrivateMessageTable->getMessageAuthor()->getId() == $user->getId()) {
            if($PrivateMessageTable->getArchivedByAuthor() == true) {
                $PrivateMessageTable->setArchivedByAuthor(false);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if($PrivateMessageTable->getDeletedByAuthor() == true) {
                $PrivateMessageTable->setDeletedByAuthor(false);
                $PrivateMessageTable->setArchivedByAuthor(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if ($PrivateMessageTable->getArchivedByAuthor() == false) {
                $PrivateMessageTable->setArchivedByAuthor(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else {
                throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
            }
        }
        else if($PrivateMessageTable->getMessageReceiver()->getId() == $user->getId()) {
            if($PrivateMessageTable->getArchivedByReceiver() == true) {
                $PrivateMessageTable->setArchivedByReceiver(false);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if($PrivateMessageTable->getDeletedByReceiver() == true) {
                $PrivateMessageTable->setDeletedByReceiver(false);
                $PrivateMessageTable->setArchivedByReceiver(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if ($PrivateMessageTable->getArchivedByReceiver() == false) {
                $PrivateMessageTable->setArchivedByReceiver(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else {
                throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
            }
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
        return $this->redirectToRoute('private_message');
    }

    /**
     * @Route("/messagerie/delete_message_{id}", name="private_message_deleted")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deletedPrivateMessage(PrivateMessageTable $PrivateMessageTable, ObjectManager $manager)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if(empty($PrivateMessageTable)) {
            throw $this->createNotFoundHttpException('Page not found');
        }
        else if($PrivateMessageTable->getMessageAuthor()->getId() == $user->getId()) {
            if($PrivateMessageTable->getDeletedByAuthor() == true) {
                $PrivateMessageTable->setArchivedByAuthor(false);
                $PrivateMessageTable->setDeletedByAuthor(false);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if($PrivateMessageTable->getDeletedByAuthor() == false) {
                $PrivateMessageTable->setDeletedByAuthor(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else {
                throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
            }
        }
        else if($PrivateMessageTable->getMessageReceiver()->getId() == $user->getId()) {
            if($PrivateMessageTable->getDeletedByReceiver() == true) {
                $PrivateMessageTable->setArchivedByReceiver(false);
                $PrivateMessageTable->setDeletedByReceiver(false);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else if($PrivateMessageTable->getDeletedByReceiver() == false) {
                $PrivateMessageTable->setDeletedByReceiver(true);
                $manager->persist($PrivateMessageTable);
                $manager->flush();
            }
            else {
                throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
            }
        }
        else {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }
        return $this->redirectToRoute('private_message');
    }

}