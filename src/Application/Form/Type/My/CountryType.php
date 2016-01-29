<?php

namespace Application\Form\Type\My;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Entity\CountryEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountryType extends AbstractType
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
        $builder->add('image', 'file', array(
            'required' => false,
        ));

        $builder->add('joiningStatus', 'choice', array(
            'choices' => CountryEntity::getJoiningStatuses(),
            'attr' => array(
                'data-help-text' => 'Who is allowed to join this country? If you choose "Open", everybody can join your country, when you choose "Invite only", only the people, that were invited via email can join your country and when you choose "Closed", nobody is able to join your country.',
            ),
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
            'data_class' => 'Application\Entity\CountryEntity',
            'validation_groups' => array('newAndEdit'),
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
            'user' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'country';
    }
}
