<?php


namespace App\Form;
use App\Entity\Commune;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CreateCommuneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('code', TextType::class, [
                'label' => 'Code'
            ])
            ->add('codeDepartement', TextType::class, [
                'label' => 'Code Departement'
            ])
            ->add('codeRegion', TextType::class, [
                'label' => 'Code Region'
            ])
            ->add('codesPostaux', CollectionType::class, [
                'entry_type' => TextType::class,
                'label' => 'Codes Postaux'
            ])
            ->add('population', IntegerType::class, [
                'label' => 'Population'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Commune::class
            ]);
    }
}