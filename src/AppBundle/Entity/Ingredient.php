<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ingredient")
 */
class Ingredient {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=500)
     *
     * @Assert\NotBlank()
     */
    private $description;
    
    /**
    * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="ingredient")
    *
    * @Assert\Valid()
    */
    private $recipes;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function __toString(){
        return 'Name: ' . $this->name . ', Description: ' . $this->description;
    }
}