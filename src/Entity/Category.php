<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; //vérifier l'unicité d'un champ en base
 
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields="name", message="il existe déà une catégorie portant ce nom")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(length=20, unique=true)
     * @var string //c'est just de la doc
     * 
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(max=20, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    private $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

 public function __toString() {
     return $this->name;
 }

}
