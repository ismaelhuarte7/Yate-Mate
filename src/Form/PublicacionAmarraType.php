<?php
namespace App\Form;

use App\Entity\Amarra;
use App\Entity\PublicacionAmarra;
use App\Entity\Usuario;
use App\Repository\AmarraRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PublicacionAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario = $options['user'];

        $builder
        ->add('fechaDesde', DateType::class, [
            'widget' => 'single_text',
            'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Por favor ingresa una fecha de inicio',
                ]),
            ],
        ])
        ->add('fechaHasta', DateType::class, [
            'widget' => 'single_text',
            'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Por favor ingresa una fecha de finalización',
                ]),
            ],
        ])
            ->add('amarra', EntityType::class, [
                'class' => Amarra::class,
             /*
                'choice_label' => 'id',
                'query_builder' => function (AmarraRepository $ar) use ($usuario) {
                    return $ar->createQueryBuilder('a')
                        ->leftJoin('a.publicacionAmarra', 'pa')
                        ->where('a.usuario = :usuario')
                        ->andWhere('pa.id IS NULL')
                        ->setParameter('usuario', $usuario);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'No tienes amarras para publicar',
                    ]),
                ],
                */
            ])
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ]);

        // Añadir la validación personalizada para las fechas
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $fechaDesde = $data->getFechaDesde();
                $fechaHasta = $data->getFechaHasta();

                if ($fechaDesde && $fechaHasta) {
                    $interval = $fechaDesde->diff($fechaHasta);

                    if ($fechaHasta <= $fechaDesde) {
                        $form->get('fechaHasta')->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
                    }

                    if ($interval->days < 1) {
                        $form->get('fechaHasta')->addError(new FormError('La diferencia entre las fechas debe ser de al menos 1 día.'));
                    }
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublicacionAmarra::class,
            'user' => null,
        ]);
    }
}
