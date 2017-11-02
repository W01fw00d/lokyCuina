<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeIngredient;

use AppBundle\Form\RecipeType;

class RecipesController extends Controller {
    private function isFieldValid($data, $fieldName) {
        return array_key_exists($fieldName, $data) && $data[$fieldName];
    }
    
    private function mapRecipe($recipe) {
        return $recipe->__toString();
    }
    
    private function mapFormChoices($result, $item) {
        $result[ucwords($item->getName())] = $item->getName();   
        return $result;
    }
    
    private function getAllIngredients() {
        $repository = $this->getDoctrine()->getRepository(Ingredient::class);
        return $repository->findAll();
    }

    private function getRecipesResultsAndErrors($requiredRecipes, $response, $errorMsg) {
        if ($requiredRecipes) {
            $response['results'] = join(';', array_map(array($this, 'mapRecipe'), $requiredRecipes));

        } else {
            $response['errors'] = $errorMsg;
        }
        
        return $response;
    }
    
    private function getRecipesListWithName($name, $response) {
        $manager = $this->getDoctrine()->getManager();
        $recipes = $manager->getRepository(Recipe::class);
        $requiredRecipes = $recipes->findByName($name);
        
        $errorMsg = 'There isn\'t any recipe registered with that name.';

        return $this->getRecipesResultsAndErrors($requiredRecipes, $response, $errorMsg);
    }

    private function getAllRecipesList($response) {
        $recipes = $this->getDoctrine()->getManager()->getRepository(Recipe::class);
        $requiredRecipes = $recipes->findAll();

        $errorMsg = 'There isn\'t any recipe registered yet.';

        return $this->getRecipesResultsAndErrors($requiredRecipes, $response, $errorMsg);
    }

    private function getRecipesList($data, $response) {
        $response = array(
            'errors' => '',
            'results' => ''
        );

        return $this->isFieldValid($data, 'name') ?
            $this->getRecipesListWithName($data['name'], $response) :
            $this->getAllRecipesList($response);
    }
    
    private function getRecipeIngredient($ingredientName, $quantity, $recipe, $ingredientsRepository, $manager) {     
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($recipe);
        $recipeIngredient->setIngredient($ingredientsRepository->findOneByName($ingredientName));
        $recipeIngredient->setQuantity($quantity);
        
        $manager->persist($recipeIngredient);
        
        return $recipeIngredient;
    }
    
    private function persistRecipe($data, $response) {
        $manager = $this->getDoctrine()->getManager();
        $recipes = $this->getDoctrine()->getRepository(Recipe::class);
        $recipe = $recipes->findOneByName($data['name']);

        if ($recipe && $this->isFieldValid($data, 'description')) {
            $recipe->setDescription($data['description']);
            $response['results'] = 'A recipe with the same name was found.; Its description has been updated.';

        } else {
            $recipe = new Recipe();
            $recipe->setName($data['name']);
            $recipe->setDescription($data['description']);
            
            $ingredientsRepository = $this->getDoctrine()->getRepository(Ingredient::class); 
                
            $ingredients = array();
            
            foreach ($data['ingredients'] as $ingredient) {
                array_push($ingredients, $this->getRecipeIngredient(
                    $ingredient['ingredient'], $ingredient['quantity'], $recipe, $ingredientsRepository, $manager)
                );
            }
            
            $recipe->setIngredients($ingredients);
            
            $manager->persist($recipe);
            $response['results'] = 'New recipe registered succesfully.';
        }

        $manager->flush();
        
        return $response;
    }

    private function formSubmit($data, $response) {
        switch ($data['action']) {
            case 'list':
                $response = $this->getRecipesList($data, $response);
                break;

            case 'new':
                $response = $this->persistRecipe($data, $response);
                break;
        }

        return $response;
    }

    /**
     * @Route(
     *     "/recipes",
     *     name="recipes",
     * )
     */
    public function showFormAction(Request $request) {
        $defaultData = array('message' => 'Type your message here');
        
        $ingredientsForm = array_reduce(
            $this->getAllIngredients(), 
            array($this, 'mapFormChoices'), 
            array()
        );
                
        $form = $this->createForm(RecipeType::class, $defaultData, array(
            'ingredients' => $ingredientsForm
        ));

        $form->handleRequest($request);

        $response = array(
            'errors' => '',
            'results' => ''
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->formSubmit($data, $response);
        }

        return $this->render('recipes/form.html.twig', array(
            'form' => $form->createView(),
            'errors' => $response['errors'],
            'results' => $response['results']
        ));
    }
}