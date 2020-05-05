<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [ "label" => "E-mail"])
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Membre" => "ROLE_USER",
                    "Contributeur" => "ROLE_CONTRIBUTEUR",
                    "Administrateur" => "ROLE_ADMIN",
                    "Développeur" => "ROLE_DEV"
                ],
                "multiple" => true,
                "label" => "Rôle"
            ])
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new Regex ([
                        "pattern" => "/^(?=.{4,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                        "message" => "Le mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
