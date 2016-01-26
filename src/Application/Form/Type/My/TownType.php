<?php

namespace Application\Form\Type\My;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Application\Entity\TownEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownType extends AbstractType
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

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $town = $event->getData();
                $form = $event->getForm();

                if ($town->getCountry() == null) {
                    $form->add('country', 'entity', array(
                        'required' => false,
                        'empty_value' => false,
                        'class' => 'Application\Entity\CountryEntity',
                        'query_builder' => function (EntityRepository $er) use ($options) {
                            return $er->createQueryBuilder('c')
                                ->where('c.user = ?1')
                                ->setParameter(
                                    1,
                                    $options['user']
                                )
                            ;
                        },
                        'attr' => array(
                            'class' => 'select-picker',
                            'data-live-search' => 'true',
                        ),
                    ));
                }
            }
        );

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
            'data_class' => 'Application\Entity\TownEntity',
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
        return 'town';
    }
}
