<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                    'name', //on défini le nom du champ qui est celui de notre bdd
                    TextType::class,  //definit un intpu type text
                    ['label' => 'Nom'] // se sont des options qu'on peut ajouter, ici que le label, mais aussi par exemple champs obligatoire ou autre
            ); //fin de méthod add()
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Category::class,  //fait le lien entre notre formulaire CategoryType et notre entité Category
        ]);
    }
}
