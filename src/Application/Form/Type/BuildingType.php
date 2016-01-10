<?php

namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingType extends AbstractType
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

        $builder->add('size', 'choice', array(
            'choices' => $this->getSizeChoices(),
        ));

        $builder->add('healthPoints', 'number');
        $builder->add('populationCapacity', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much population capacity do you get by building this building?',
            ),
        ));
        $builder->add('storageCapacity', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much storage capacity do you get by building this building?',
            ),
        ));
        $builder->add('buildTime', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much time does it take to build this building (in seconds)?',
            ),
        ));

        $builder->add('buildingResources', 'collection', array(
            'type' => new BuildingResourceType(),
            'label' => 'Building resources cost',
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
            'data_class' => 'Application\Entity\BuildingEntity',
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
        return 'building';
    }

    /**
     * @return array
     */
    public function getTypeChoices()
    {
        return array(
            'civil' => 'Civil',
            'defensive' => 'Defensive',
            'military' => 'Military',
            'technology' => 'Technology',
            'other' => 'Other',
        );
    }

    /**
     * @return array
     */
    public function getSizeChoices()
    {
        return array(
            '1x1' => '1x1',
            '2x2' => '2x2',
            '3x3' => '3x3',
            '4x4' => '4x4',
            '1x2' => '1x2',
            '1x3' => '1x3',
            '1x4' => '1x4',
            '2x4' => '2x4',
        );
    }
}
