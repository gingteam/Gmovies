<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield AssociationField::new('director')
            ->setCrudController(DirectorCrudController::class)
            ->setColumns(6)
            ->hideOnIndex();
        yield AssociationField::new('actors')
            ->setCrudController(ActorCrudController::class)
            ->setColumns(6)
            ->hideOnIndex();
        yield AssociationField::new('genres')
            ->setCrudController(GenreCrudController::class);
        yield TextareaField::new('description');
        yield ImageField::new('poster')->setUploadDir('public/uploads')->setBasePath('uploads');
    }
}
