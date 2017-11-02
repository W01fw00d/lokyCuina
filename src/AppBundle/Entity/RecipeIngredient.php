<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recipes_ingredients")
 */
class RecipeIngredient {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     *
     * @Assert\Valid()
     */
    private $recipe;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="recipes")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id")
     *
     * @Assert\Valid()
     */
    private $ingredient;
    
    /**
     * @ORM\Column(type="integer", length=10)
     *
     * @Assert\NotBlank()
     */
    private $quantity;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getRecipe() {
        return $this->recipe;
    }

    public function setRecipe($recipe) {
        $this->recipe = $recipe;
    }
    
    public function getIngredient() {
        return $this->ingredient;
    }

    public function setIngredient($ingredient) {
        $this->ingredient = $ingredient;
    }
    
    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}