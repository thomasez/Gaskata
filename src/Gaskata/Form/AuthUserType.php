<?php

namespace Gaskata\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userIdent')
            ->add('domain')
            ->add('email')
            ->add('password')
            ->add('state')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gaskata\Entity\AuthUser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gaskata_authuser';
    }
}
