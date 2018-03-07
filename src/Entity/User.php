<?php

namespace App\Entity; //vérifier l'unicité d'un champ en base

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;  //nécessaire car UserInterface contiend des méthodes dont l'encodage à besoin pour bosser.
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="cette email existe déjà")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @var string
     */
    private $firstname;
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     * @var string
     */
    private $lastname;

    /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(message="cet email n'est pas valide")
     * @var string
     */
    private $email;
    
    /**
     * @ORM\Column()
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(length=20)
     * @var string
     */
    private $role = 'ROLE_USER';
    
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $plainPassword;

    
    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getFullName(){
        return $this->firstname. ' '.strtoupper($this->lastname);
    }
    
    public function eraseCredentials() {
        //si on veut enlever une partie des droit à un user, dans cet exemple on laisse vide
    }

    public function getRoles() {
        return array($this->role);
    }

    public function getSalt() {
        //c'est pour l'encodage de mdp, certain systeme de cryptage ajout un "grain de sel".
        //on laisse a vide dans cette exemple
    }

    public function getUsername(): string {
        //UserName c'est l'identifiant de user > ici on renvoi donc l'email
        return $this->email;
    }

    public function __toString() {
        return $this->getFullName();
    }
    
}

