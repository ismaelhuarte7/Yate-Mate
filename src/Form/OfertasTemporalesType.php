<?php

namespace App\Form;

use DateTime;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfertasTemporalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $marinas=['Norte','Centro','Sur','Este','Oeste','Delta','Bahia','Atlantico'];

        $tamaños=['Chica','Mediana','Grande'];
    
        $marinasAsociativo = array_combine($marinas, $marinas);
        $tamañosAsociativo = array_combine($tamaños, $tamaños);
        

        $builder
            ->add('desde',TypeDateType::class,[
                'required'=> false,
                'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
                'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
            ])
            ->add('hasta',TypeDateType::class,[
                'required'=>false,
                'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
                'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
            ])
            ->add('tamano',ChoiceType::class, [
                'choices' => $tamañosAsociativo,
                'required'=>false,
                'placeholder' => 'Tamaño',
                
            ])
            ->add('marinas',ChoiceType::class, [
                'choices' =>$marinasAsociativo,
                'required'=>false,
                'placeholder' => 'Marinas',
                'label' => 'marinas'
            ])



            ->add('Filtrar',SubmitType::class)
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
