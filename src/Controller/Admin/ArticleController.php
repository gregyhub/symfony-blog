<?php

namespace App\Controller\Admin;
//modifier le namespace pour ajouter \Admin


use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
    
    /**
     * @Route("/article")
     */
class ArticleController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
       
       if(is_null($id)){
           $article = new Article(); //on instancie notre entité category
           //l'auteur de l'article est l'utilisateur connecté
           $article->setAuthor($this->getUser());
       }else{
           //$article = $em->getRepository(Article::class)->find($id);
           $article = $em->find(Article::class, $id);
       }
       $form = $this->createForm(ArticleType::class, $article); 
        //le createForm est un méthode de controller 
        /// le categoryType est une méthode de formulaire. 
        //on envoie aussi en paramtre notre objet category
       
       $form->handleRequest($request); //le formulaire traite la requete HTTP
       //le formulaire a été envoyé ou NON ? si oui, il fait le mapping avec notre objet category et effectue les Setter à notre place
       //si le formulaire a été envoyé
       if($form->isSubmitted()){
           //si il n'y a pas d'erreur de validation du formulaire > dans la class category
           if($form->isValid()){
               //prépare l'enregistement en bdd
               $em->persist($article); //on peut faire plusieurs persist puis 1 seul flush a la fin
               //fait l'enregistement en bdd
               $em->flush(); //execute des transaction SQL. si tout passe va envoie en bdd, sinon fait un rollback
               $this->addFlash('success', 'l\'article '.$article->getTitle().' a été enregistrée'); //ajout du message flash
               return $this->redirectToRoute('app_admin_article_index'); //redirection
           }
           else{
               $this->addFlash('error', 'erreur'); //ajout du message flash
           }
       }
          return $this->render('admin/article/edit.html.twig', [
          'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id) {
        
       $em = $this->getDoctrine()->getManager();
       $article = $em->find(Article::class,$id);
       $em->remove($article);
       $em->flush();
       $this->addFlash('success', 'l article '.$article->getTitle().' a été supprimée'); //ajout du message flash
       return $this->redirectToRoute('app_admin_article_index'); //redirection
    }
}
