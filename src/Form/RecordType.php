<?php

namespace App\Form;

use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Artist;
use Symfony\Component\Validator\Constraints\File;


class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [ "label" => "Titre" ])
            ->add('description')
            ->add('releaseAt', DateType::class, [
                "widget" => "single_text",
                "label" => "Sortie le"
            ])
            ->add("artist", EntityType::class, [ 
                "class" => Artist::class,
                "choice_label" => "name",
                "label" => "Artiste"
            ])
            ->add("cover", FileType::class, [
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => [ "image/gif", "image/jpeg", "image/png" ],
                        "mimeTypesMessage" => "Les formats de fichier autorisés sont gig, jpeg, png",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Le fichier ne doit pas faire plus de 2Mo"
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}
