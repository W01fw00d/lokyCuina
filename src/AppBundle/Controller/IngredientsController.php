<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Ingredient;

use AppBundle\Form\IngredientType;

class IngredientsController extends Controller {
    private function isFieldValid($data, $fieldName) {
        return array_key_exists($fieldName, $data) && $data[$fieldName];
    }
    
    private function mapIngredient($ingredient) {
        return $ingredient->__toString();
    }

    private function getIngredientsResultsAndErrors($requiredIngredients, $response, $errorMsg) {
        if ($requiredIngredients) {
            $response['results'] = join(';', array_map(array($this, 'mapIngredient'), $requiredIngredients));

        } else {
            $response['errors'] = $errorMsg;
        }
        
        return $response;
    }
    
    private function getIngredientsListWithName($name, $response) {
        $manager = $this->getDoctrine()->getManager();
        $ingredients = $manager->getRepository(Ingredient::class);
        $requiredIngredients = $ingredients->findByName($name);
        
        $errorMsg = 'There isn\'t any ingredient registered with that name.';

        return $this->getIngredientsResultsAndErrors($requiredIngredients, $response, $errorMsg);
    }

    private function getAllIngredientsList($response) {
        $ingredients = $this->getDoctrine()->getManager()->getRepository(Ingredient::class);
        $requiredIngredients = $ingredients->findAll();

        $errorMsg = 'There isn\'t any ingredient registered yet.';

        return $this->getIngredientsResultsAndErrors($requiredIngredients, $response, $errorMsg);
    }

    private function getIngredientsList($data, $response) {
        $response = array(
            'errors' => '',
            'results' => ''
        );

        return $this->isFieldValid($data, 'name') ?
            $this->getIngredientsListWithName($data['name'], $response) :
            $this->getAllIngredientsList($response);
    }
    
    private function persistIngredient($data, $response) {
        $manager = $this->getDoctrine()->getManager();
        $ingredients = $this->getDoctrine()->getRepository(Ingredient::class);
        $ingredient = $ingredients->findOneByName($data['name']);

        if ($ingredient && $this->isFieldValid($data, 'description')) {
            $ingredient->setDescription($data['description']);
            $response['results'] = 'An ingredient with the same name was found.; Its description has been updated.';

        } else {
            $ingredient = new Ingredient();
            $ingredient->setName($data['name']);
            $ingredient->setDescription($data['description']);
            
            $manager->persist($ingredient);
            $response['results'] = 'New ingredient registered succesfully.';
        }

        $manager->flush();
        
        return $response;
    }

    private function formSubmit($data, $response) {
        switch ($data['action']) {
            case 'list':
                $response = $this->getIngredientsList($data, $response);
                break;

            case 'new':
                $response = $this->persistIngredient($data, $response);
                break;
        }

        return $response;
    }

    /**
     * @Route(
     *     "/ingredients",
     *     name="ingredients",
     * )
     */
    public function showFormAction(Request $request) {
        $defaultData = array('message' => 'Type your message here');
        
        $form = $this->createForm(IngredientType::class, $defaultData);

        $form->handleRequest($request);

        $response = array(
            'errors' => '',
            'results' => ''
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->formSubmit($data, $response);
        }

        return $this->render('ingredients/form.html.twig', array(
            'form' => $form->createView(),
            'errors' => $response['errors'],
            'results' => $response['results']
        ));
    }
}