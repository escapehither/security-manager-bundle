<?php

namespace StarterKit\SecurityManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditUserRoles extends AbstractType
{
    private $rolesHierarchy;

    public function __construct( $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices' => [//$this->rolesHierarchy
                    'SUPER ADMIN' => [0 =>'ROLE_SUPER_ADMIN'],
                    'ADMIN' => [0 =>'ROLE_ADMIN'],
                    'MANAGER ACCESS ONLY' =>[0=>'ROLE_MANAGER'],
                    // ...
                    "ROLE_MANAGE_USER" => [
                        0 => "ROLE_MANAGE_USER",
                        1 => "ROLE_USER_CREATE",
                        2 => "ROLE_USER_SHOW",
                        3 => "ROLE_USER_EDIT",
                        4 => "ROLE_USER_DELETE",
                        5 => "ROLE_USER_UNDELETE",

                ],
                    "ROLE_MANAGE_CUSTOMER" => [
                        0 => "ROLE_MANAGE_CUSTOMER",
                        1 => "ROLE_CUSTOMER_CREATE",
                        2 => "ROLE_CUSTOMER_SHOW",
                        3 => "ROLE_CUSTOMER_EDIT",
                        4 => "ROLE_CUSTOMER_DELETE",
                        5 => "ROLE_CUSTOMER_UNDELETE",

                    ],
                    "ROLE_MANAGE_RESERVATION" => [
                        0 => "ROLE_MANAGE_RESERVATION",
                        1 => "ROLE_RESERVATION_CREATE",
                        2 => "ROLE_RESERVATION_SHOW",
                        3 => "ROLE_RESERVATION_EDIT",
                        4 => "ROLE_RESERVATION_DELETE",
                        5 => "ROLE_RESERVATION_UNDELETE",
                    ],
                    "ROLE_MANAGE_ROOM" => [
                        0 => "ROLE_MANAGE_ROOM",
                        1 => "ROLE_ROOM_CREATE",
                        2 => "ROLE_ROOM_SHOW",
                        3 => "ROLE_ROOM_EDIT",
                        4 => "ROLE_ROOM_DELETE",
                        5 => "ROLE_ROOM_UNDELETE",
                    ],
            ]
            ])
            // other fields...
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
