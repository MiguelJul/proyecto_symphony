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
    {   $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $repositori = $doctrine->getRepository(Equip::class);
        $equips = $repositori->findAll();
        return $this->render('inici.html.twig',array('equips'=>$equips,"fechacompleta"=>$fechacompleta));
    }
    #[Route('/dates' ,name:'dates')]
    public function dates()
    {
    $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
    $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
    Date::setLocale('ca_ES');
    $hui = Date::now();
    $huitexto=$hui->format('w/d/m/Y');
    $huitexto=explode("/",$huitexto);
    $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
    $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");
    return new Response($fechacompleta);
    }
}
    ?>