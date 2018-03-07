<?php

namespace App\Controller\Admin;
//modifier le namespace pour ajouter \Admin


use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
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
       $originalImage = null;
       if(is_null($id)){
           $article = new Article(); //on instancie notre entité category
           //l'auteur de l'article est l'utilisateur connecté
           $article->setAuthor($this->getUser());
       }else{
           //$article = $em->getRepository(Article::class)->find($id);
           $article = $em->find(Article::class, $id);
           if(!is_null($article->getPicture())){
               $originalImage = $article->getPicture();
               $article->setPicture(
                new File($this->getParameter('img_directory').'/'.$article->getPicture())
            );
               dump($article->getPicture());
           }
           
       }
       $form = $this->createForm(ArticleType::class, $article); 
        //le createForm est un méthode de controller 
        /// le categoryType est une méthode de formulaire. 
        //on envoie aussi en paramtre notre objet category
       
       $form->handleRequest($request); //le formulaire traite la requete HTTP
       //le formulaire a été envoyé ou NON ? si oui, il fait le mapping avec notre objet category et effectue les Setter à notre place
       //si le formulaire a été envoyé
       if($form->isSubmitted()){
           if($form->isValid()){
           //si il n'y a pas d'erreur de validation du formulaire > dans la class category
            $file =  $article->getPicture(); //équivalent à puisqu le formulaire est mappé sur article : $form['picture']->getData(); 
            
            if(!is_null($file)){
                $filename= uniqid().'.'.$file->guessExtension();
                $article->setPicture($filename);
                $file->move($this->getParameter('img_directory'), $filename);
                
                if(!is_null($originalImage)){
                    //je rentre dans cette condition si le champ file est rempli, et uniquement si original image n'est pas null
                    //sioriginalImage est null c'est que c'est l'ajout un nouvel articl ou la modification d'un article qui n'avait pas d'image
                    unlink($this->getParameter('img_directory').'/'. $originalImage);
                }
            }else{
                if($form->has('remove_image') && $form->get('remove_image')->getData()){
                    //getdata envoie un booléan pour un check box, ou un chaine de caractère pour un input
                    unlink($this->getParameter('img_directory').'/'. $originalImage);
                    $article->setPicture(null);
                }else{
                    $article->setPicture($originalImage);
                }
            }
                
            
            //prépare l'enregistement en bdd
            $em->persist($article); //on peut faire plusieurs persist puis 1 seul flush a la fin
            //fait l'enregistement en bdd
            $em->flush(); //execute des transaction SQL. si tout passe va envoie en bdd, sinon fait un rollback
            $this->addFlash('success', 'l\'article '.$article->getTitle().' a été enregistrée'); //ajout du message flash
            return $this->redirectToRoute('app_admin_article_index'); //redirection
           } else{
              $this->addFlash('error', 'erreur'); //ajout du message flash
            }
       }
       
          return $this->render('admin/article/edit.html.twig', [
          'form' => $form->createView(),
          'original_image' => $originalImage
       ]);
    }
    
    /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id) {
        
       $em = $this->getDoctrine()->getManager();
       $article = $em->find(Article::class,$id);
       if(!is_null($article->getPicture())){
            unlink($this->getParameter('img_directory').'/'. $article->getPicture());
       }
       $em->remove($article);
       $em->flush();
       $this->addFlash('success', 'l article '.$article->getTitle().' a été supprimée'); //ajout du message flash
       return $this->redirectToRoute('app_admin_article_index'); //redirection
    }
}
