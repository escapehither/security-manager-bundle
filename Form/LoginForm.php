<?php

namespace EscapeHither\SecurityManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('_username')
          ->add('_password', PasswordType::class)
        ;

    }
    public function getBlockPrefix()
    {
        return null;
    }

    
}
