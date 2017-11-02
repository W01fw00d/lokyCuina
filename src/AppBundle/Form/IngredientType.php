<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IngredientType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $formClasses = array(
            'class' => 'form-control form_input'
        );

        $builder
            ->add('action', ChoiceType::class, array(
                'label' => 'Database Interaction',
                'attr' => $formClasses,
                'choices' => array(
                    'List ingredients' => 'list',
                    'Register new ingredient' => 'new'   
                )
            ))

            ->add('name', TextType::class, array(
                'label' => 'Name',
                'attr' => $formClasses,
                'required' => false
            ))
            
            ->add('description', TextareaType::class, array(
                'label' => 'Description *',
                'attr' => $formClasses,
                'required' => false
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Apply',
                'attr' => array(
                    'class' => 'form-button'
                )));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }
}