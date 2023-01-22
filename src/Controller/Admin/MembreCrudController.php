<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

use App\Entity\Membre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MembreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }
    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('nom'),
        TextField::new('cognoms'),
        EmailField::new('email'),
        DateField::new('dataNaiximent'),
        NumberField::new('nota'),
        ImageField::new('imatgeperfil')
        ->setBasePath('/Green/assets/img/membres/') // carpeta img
        ->setUploadDir('/public/Green/assets/img/membres/')
        ->setRequired(false),
        AssociationField::new('equip'),
        ];
        }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
