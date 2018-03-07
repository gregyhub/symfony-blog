<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $lastArticles = $repo->findLatest(3);
        dump($lastArticles);
        return $this->render('index/index.html.twig',
                [
                    'last_articles' => $lastArticles
                ]
                );
    }
}
