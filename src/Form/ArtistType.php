<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [ 
                "label" => "Nom de l'artiste",
                "constraints" => [
                    new NotBlank([ "message" => "Veuillez entrer le nom de l'artiste"]),
                    new Length([
                        "min" => 2,
                        "minMessage" => "Le nom de l'artiste doit comporter au moins 2 caractères",
                        "max" => 20,
                        "maxMessage" => "Le nom de l'artiste ne doit pas dépasser 20 caractères"
                    ])
                ],
                // "required" => false
            ])
            ->add('description', TextareaType::class, [ "required" => false ])
            ->add("enregistrer", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}