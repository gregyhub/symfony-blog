<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(  'title',
                    TextType::class,
                    [
                         'label' => 'Titre'
                    ]
                  )
            ->add(  'content',
                    TextareaType::class,
                    [
                     'label' => 'Contenu'
                    ]
              )
            ->add(  'description',
                    TextareaType::class,
                    [
                     'label' => 'Descrption'
                    ]
              )
            ->add(  'category',
                     EntityType::class,
                    [
                     'label' => 'Catégorie',
                     'class' => Category::class,
                        //nom du champ qui s'affiche dans les options : ici le name de la category
                    'choice_label' => 'name',
                     'placeholder' => 'choisissez une catégorie'
                    ]
              )
            ->add(  'picture',
                    FileType::class,
                    [
                     'label' => 'Image/Photo',
                      'required' => false
                    ]
              )
        ;
        
        if(!is_null($options['data']->getPicture())){
            //dans $option['data']on a l'entité à la quel est lié le formulaire, ici l'article. donc je peux acceder au getPicture de article
            $builder->add(
                        'remove_image',
                        CheckboxType::class,
                         [
                             'label' => "supprimer l'image",
                             'required' => false,
                             'mapped'=> false //champ non relié à l'entité article
                         ]
                    );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Article::class,
        ]);
    }
}
