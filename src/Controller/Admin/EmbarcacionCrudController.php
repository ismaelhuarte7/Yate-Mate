<?php

namespace App\Controller\Admin;

use App\Entity\Embarcacion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;

class EmbarcacionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Embarcacion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating(),
            TextField::new('matricula')->hideWhenUpdating(),
            TextField::new('nombre'),
            CountryField::new('bandera'),
            Field::new('manga')->setHelp('Mts'),
            Field::new('eslora')->setHelp('Mts'),
            Field::new('puntal')->setHelp('Mts'),
            TextField::new('tipo')->onlyOnIndex(),
            ChoiceField::new('tipo')->hideOnIndex()
            ->setFormTypeOptions([ // Permite seleccionar varios roles
                'choices' => [
                    'Yate' => 'Yate',
                    'Lancha' => 'Lancha',
                    'Bote' => 'Bote',
                ],
        ]),
            AssociationField::new('usuario')->autocomplete(),
            AssociationField::new('amarra')->hideWhenUpdating()
        ];
    }
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Embarcacion) {
            $amarra = $entityInstance->getAmarra();
            if ($amarra) {
                $amarra->setEmbarcacion(null);
                $entityInstance->setAmarra(null);
                $entityManager->persist($amarra);
                $entityManager->flush();
            }
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
    
}
