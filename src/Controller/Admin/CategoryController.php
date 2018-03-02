<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('admin/category/index.html.twig', [
          
        ]);
    }
}
