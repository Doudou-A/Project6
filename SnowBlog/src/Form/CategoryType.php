<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('figures', EntityType::class, [
                'class' => Figure::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('figure')
                    ->where('figure.category is NULL');
                },
                'mapped' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
