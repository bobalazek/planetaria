<?php

namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictResourceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('resource', 'entity', array(
            'required' => false,
            'empty_value' => false,
            'class' => 'Application\Entity\ResourceEntity',
            'attr' => array(
                'class' => 'select-picker',
                'data-live-search' => 'true',
            ),
        ));
        $builder->add('amount', 'number', array(
            'attr' => array(
                'data-help-text' => 'How much (in total) is there of this resource?',
            ),
        ));
        $builder->add('amountLeft', 'number');
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Entity\DistrictResourceEntity',
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
        return 'districtResource';
    }
}
