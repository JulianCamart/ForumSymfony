<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/news", name="news")
     */
    public function news()
    {
        return $this->render('home/news.html.twig');
    }

    /**
     * @Route("/admin/show_messages", name="admin_show_messages")
     */
    public function showMessages()
    {
        return $this->render('Admin/show_messages.html.twig');
    }
}
