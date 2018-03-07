<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{id}")
     */
    public function article(Article $article)
    {
        
        return $this->render('article/article.html.twig', [
           'article' => $article
        ]);
    }
}
