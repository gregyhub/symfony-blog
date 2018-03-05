<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/inscription")
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $em = $this->getDoctrine()->getManager();
        
        $form->handleRequest($request); //le formulaire traite la requete HTTP
       //le formulaire a été envoyé ou NON ? si oui, il fait le mapping avec notre objet category et effectue les Setter à notre place
       //si le formulaire a été envoyé
       if($form->isSubmitted()){
           //si il n'y a pas d'erreur de validation du formulaire > dans la class category
           if($form->isValid()){
               dump($user->getPlainPassword());
                $encoded = $encoder->encodePassword($user, 'mdp');
                $user->setPassword($encoded);
               //prépare l'enregistement en bdd
               $em->persist($user); //on peut faire plusieurs persist puis 1 seul flush a la fin
               //fait l'enregistement en bdd
               $em->flush(); //execute des transaction SQL. si tout passe va envoie en bdd, sinon fait un rollback
               $this->addFlash('success', 'la membre '.$user->getFullName().' a été enregistrée'); //ajout du message flash
               return $this->redirectToRoute('app_security_register'); //redirection
           }
           else{
               $this->addFlash('error', 'erreur'); //ajout du message flash
           }
       }
        
        
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
