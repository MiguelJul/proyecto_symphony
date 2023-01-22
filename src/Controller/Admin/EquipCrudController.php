<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\FileUploadType;
//sprintf('upload_%s_%s.%s','Green/assets/img/equips/',$file->getFilename(), $file->guessExtension()))
use App\Entity\Equip;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equip::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('cicle'),
            TextField::new('curs'),
            NumberField::new('nota'),
            ImageField::new('imatge')
            ->setBasePath('/') // carpeta img
            ->setUploadDir('/public/Green/assets/img/equips/')
            ->setUploadedFileNamePattern('Green/assets/img/equips/[slug].[extension]')
            ->setRequired(false),
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
