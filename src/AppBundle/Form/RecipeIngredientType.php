<?php

namespace AppBundle\Form;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeIngredient;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RecipeIngredientType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {        
        $formClasses = array(
            'class' => 'form-control form_input'
        );

        $builder
            ->add('ingredient', ChoiceType::class, array(
                    'label' => 'Ingredient *',
                    'attr' => $formClasses,
                    'choices' => $options['ingredients'],
                    'required' => false
                
            ))
            ->add('quantity', NumberType::class, array(
                    'label' => 'Quantity *',
                    'attr' => $formClasses,
                    'required' => false
                
            ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null,
            'ingredients' => RecipeIngredient::class
        ));
    }
}