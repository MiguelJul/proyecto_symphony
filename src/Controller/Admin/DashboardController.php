<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\EquipCrudController;
use App\Entity\Equip;
use App\Entity\Membre;
use App\Entity\Usuari;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
    $routeBuilder = $this->container->get(AdminUrlGenerator::class);
    return $this->redirect($routeBuilder->
    setController(EquipCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
    return Dashboard::new()->setTitle('Prova Bundles');
    }

    public function configureMenuItems(): iterable
    {
    yield MenuItem::linktoDashboard('Desplegable', 'fa fa-home');
    yield MenuItem::linkToCrud('Equips', 'fas fa-list',
    Equip::class);
    yield MenuItem::linkToCrud('Membres', 'fas fa-list',
    Membre::class);
    yield MenuItem::linkToCrud('Usuaris', 'fas fa-list',
    Usuari::class);
    yield MenuItem::linkToRoute('Inici', 'fas fa-home', 'inici');
    }

    
}


