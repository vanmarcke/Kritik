<?php

namespace App\Form;

use App\Entity\Ranking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;

class NotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', null, [
                "constraints" => [
                    new GreaterThanOrEqual([
                        "value" => 0,
                        "message" => "La note doit être supérieure ou égale à 0"
                    ]),
                    new LessThan([
                        "value" => 11,
                        "message" => "La note ne doit pas dépasser 10"
                    ])
                    ],
                    "attr" => [ "min" => 0, "max" => 10 ]
            ])
            ->add('comment', null, ["required" => false ])
            // ->add('record')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ranking::class,
        ]);
    }
}
