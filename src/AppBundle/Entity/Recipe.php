<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recipe")
 */
class Recipe {
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
    * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="recipe")
    *
    * @Assert\Valid()
    */
    private $ingredients;
    
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
    
    public function getIngredients() {
        return $this->ingredients;
    }

    public function setIngredients($ingredients) {
        $this->ingredients = $ingredients;
    }
    
    public function __toString(){    
        $ingredients = $this->ingredients;
        $ingredientsString = '';
        
        foreach ($ingredients as $ingredient) {            
            $ingredientsString .= 'Ingredient: ' . $ingredient->getIngredient()->getName() . ', Quantity: ' . $ingredient->getQuantity() . ' | ';
        }
                
        return ', Name: ' . $this->name . ', Description: ' . $this->description . ', Ingredients: [' . $ingredientsString . ']';
    }
}