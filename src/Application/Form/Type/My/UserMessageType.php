<?php

namespace Application\Form\Type\My;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserMessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', 'text');

        $builder->add('content', 'textarea', array(
            'required' => false,
        ));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $userMessage = $event->getData();
                $form = $event->getForm();

                if ($userMessage->getUser() == null) {
                    $form->add('user', 'entity', array(
                        'required' => false,
                        'empty_value' => false,
                        'class' => 'Application\Entity\UserEntity',
                        'query_builder' => function (EntityRepository $er) use ($options) {
                            return $er->createQueryBuilder('u')
                                ->where('u.id <> :id')
                                ->setParameter(
                                    'id',
                                    $options['user']->getId()
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
            'data_class' => 'Application\Entity\UserMessageEntity',
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
        return 'userMessage';
    }
}
