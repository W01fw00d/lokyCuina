<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Ingredient;
use AppBundle\Entity\RecipeIngredient;
use AppBundle\Entity\Recipe;

class LoadUserData implements FixtureInterface {
    public function load(ObjectManager $manager) {
        $ingredientsNames = array(
            'Tomato',
            'Cheese',
            'Fish',
            'Beef',
            'Potato',
            'Egg',
            'Bread',
            'Chicken',
            'Pasta',
            'Rice'
        );
        
        $recipeNames = array(
            'Pasta Bolognese',
            'Chinese Rice',
            'Fish and chips',
            'Wine Tenderloin',
            'Crispy Chicken',
            'Spanish omelette',
            'Chicken Soup',
            'Baked Potato',
            'Hamburguer',
            'Beef Cauldron'
        );
        
        // Ingredients
        for ($i = 0; $i < 10; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientsNames[$i]);
            $ingredient->setDescription('Ingredient Description '.$i);
            $manager->persist($ingredient);
        }
        
        // Recipe
        for ($i = 0; $i < 10; $i++) {
            $recipe = new Recipe();
            $recipe->setName($recipeNames[$i]);
            $recipe->setDescription('Recipe Description '.$i);

            $manager->persist($recipe);
        }
        
        $manager->flush();
        $ingredientsRepository = $manager->getRepository(Ingredient::class);
        $recipeRepository = $manager->getRepository(Recipe::class);
        
        // Recipes Ingredients
        for ($i = 0; $i < 10; $i++) {
            $recipeIngredient = new RecipeIngredient();
            
            $recipeFromRepository = $recipeRepository->findAll()[$i];
            $recipeIngredient->setRecipe($recipeFromRepository);
            
            $ingredientFromRepository = $ingredientsRepository->findAll()[$i];
            $recipeIngredient->setIngredient($ingredientFromRepository);
            
            $recipeIngredient->setQuantity($i + 1);
            
            $manager->persist($recipeIngredient);
        }
        
        $manager->flush();
    }
}