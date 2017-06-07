<?php

namespace EscapeHither\SecurityManagerBundle\Form;

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
                'choices' => $this->getRolesTab()
            ])
            // other fields...
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
    /**
     * @return array
     */
    private function getRolesTab()
    {

        $roleTab = [
          'SUPER ADMIN' => [0 => 'ROLE_SUPER_ADMIN'],
          'ADMIN' => [0 => 'ROLE_ADMIN'],
          'MANAGER ACCESS ONLY' => [0 => 'ROLE_MANAGER']
        ];
        foreach ($this->rolesHierarchy as $key => $value) {
            $tabSting = explode("_", $key);
            if (in_array('MANAGE', $tabSting)) {

                $roleTab[$key] = $value;
            }

        }

        return $roleTab;
    }

}
