<?php

namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('slug', 'text');
        $builder->add('description', 'textarea', array(
            'required' => false,
        ));

        $builder->add('type', 'choice', array(
            'choices' => $this->getTypeChoices(),
        ));

        $builder->add('healthPoints', 'number');
        $builder->add('capacity', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much capacity do you get by building this building?',
            ),
        ));
        $builder->add('buildTime', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much time does it take to build this building (in seconds)?',
            ),
        ));

        $builder->add('itemResources', 'collection', array(
            'type' => new ItemResourceType(),
            'label' => 'Item resources cost',
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'prototype' => true,
            'cascade_validation' => true,
            'error_bubbling' => false,
            'by_reference' => false,
        ));

        $builder->add('submitButton', 'submit', array(
            'label' => 'Save',
            'attr' => array(
                'class' => 'btn-primary btn-lg btn-block',
            ),
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Entity\ItemEntity',
            'validation_groups' => array('newAndEdit'),
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }

    /**
     * @return array
     */
    public function getTypeChoices()
    {
        return array(
            '???' => '???',
        );
    }
}
