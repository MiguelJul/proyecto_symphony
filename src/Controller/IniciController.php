<?php
namespace App\Controller;
use App\Entity\Equip;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Jenssegers\Date\Date;
class IniciController extends AbstractController
{
    
    #[Route('/' ,name:'inici')]
    public function inici(ManagerRegistry $doctrine)
    {
        $repositori = $doctrine->getRepository(Equip::class);
        $equips = $repositori->findAll();
        return $this->render('inici.html.twig',array('equips'=>$equips));
    }
    #[Route('/dates' ,name:'dates')]
    public function dates()
    {
    Date::setLocale('ca_ES');
    $hui = Date::now();
    return new Response($hui->format('d/m/Y') . ', carregat a les ');
    }
}
    ?>