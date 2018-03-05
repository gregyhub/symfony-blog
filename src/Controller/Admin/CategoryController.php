<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $repository = $this->getDoctrine()->getRepository(Category::class); // c'est un raccourcis de this->getDoctrine()->getManager()->getRepository(Person::class)
        $categories = $repository->findAll();
        return $this->render('admin/category/index.html.twig', [
          'categories' => $categories,
        ]);
    }
    /**
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function edit(Request $request, $id)
    {
       $em = $this->getDoctrine()->getManager();
       
       if(is_null($id)){
           $category = new Category(); //on instancie notre entité category
       }else{
           $category = $em->getRepository(Category::class)->find($id);
       }
       $form = $this->createForm(CategoryType::class, $category); 
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
               $em->persist($category); //on peut faire plusieurs persist puis 1 seul flush a la fin
               //fait l'enregistement en bdd
               $em->flush(); //execute des transaction SQL. si tout passe va envoie en bdd, sinon fait un rollback
               $this->addFlash('success', 'la catégorie '.$category->getName().' a été enregistrée'); //ajout du message flash
               return $this->redirectToRoute('app_admin_category_index'); //redirection
           }
           else{
               $this->addFlash('error', 'erreur'); //ajout du message flash
           }
       }
       
       
       return $this->render('admin/category/edit.html.twig', [
          'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/delete/{id}")
     */
    public function delete( $id)
    {
       $em = $this->getDoctrine()->getManager();
       //raccourci pour $em->getRepository(Category::class)->find($id);
       $category = $em->find(Category::class, $id);
       
       $em->remove($category);
       $em->flush();
       $this->addFlash('success', 'la catégorie '.$category->getName().' a été supprimée'); //ajout du message flash
       return $this->redirectToRoute('app_admin_category_index'); //redirection
 
    }
}
