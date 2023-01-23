<?php
namespace App\Controller; 
use Jenssegers\Date\Date;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils)
{$dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
    $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
    Date::setLocale('ca_ES');
    $hui = Date::now();
    $huitexto=$hui->format('w/d/m/Y');
    $huitexto=explode("/",$huitexto);
    $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
    $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");


$error = $authenticationUtils->getLastAuthenticationError();
$lastUsername = $authenticationUtils->getLastUsername();
return $this->render('login.html.twig',
array('error' => $error,'lastUsername' => $lastUsername,"fechacompleta"=>$fechacompleta));
}

}
?>

