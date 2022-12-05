<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class EquipsController extends AbstractController
{
private $equips = array(
    array("codi" => "1", "nom" => "Equip Roig", "cicle" =>"DAW",
    "curs" =>"22/23", "membres" =>
    array("Elena","Vicent","Joan","Maria"),"foto"=>"Green/assets/img/rojo.jpg"),
    array("codi" => "2", "nom" => "Equip Blau", "cicle" =>"DAM",
    "curs" =>"22/23", "membres" =>
    array("Carlos","Irene","Lorena","Ricardo"),"foto"=>"Green/assets/img/azul.jpg"),
    array("codi" => "3", "nom" => "Equip Groc", "cicle" =>"ASIR",
    "curs" =>"22/23", "membres" =>
    array("Toni","Ximo","Paula","Laura"),"foto"=>"Green/assets/img/verde.jpeg"),
    array("codi" => "4", "nom" => "Equip Negre", "cicle" =>"ASIX",
    "curs" =>"22/23", "membres" =>
    array("Marc","Sara","Roman","Aitor"),"foto"=>"Green/assets/img/negro.jpg")
);

#[Route('/equip/{codi}' ,name:'dades_equip', requirements: ['codi' => '\d+'])]
public function fitxa($codi=1)
{
$resultat = array_filter($this->equips,
function($equip) use ($codi)
{
return $equip["codi"] == $codi;
});

if (count($resultat) > 0)
return $this->render('equip.html.twig', array(
    'contactes' => array_shift($resultat),'codi'=>$codi));
else
return $this->render('equip.html.twig', array(
    'contactes' => NULL,'codi'=>NULL));
}

#[Route('/equip/{text}' ,name:'buscar_equips')]
public function buscar($text)
{
$resultat = array_filter($this->equips,
function($equip) use ($text)
{
return strpos($equip["nom"], $text) !== FALSE;
});
$resposta = "";
if (count($resultat) > 0)
{

foreach ($resultat as $equip){
$membres="";
foreach($equip["membres"] as $membre){
$membres.=$membre.", ";
}
$resposta .= "<ul><li>" . $equip["nom"] . "</li>" .
"<li>" . $equip["cicle"] . "</li>" .
"<li>" . $equip["curs"] . "</li>".
"<li>" . $membres . "</li>".
"</ul>";
}
return new Response("<html><body>" . $resposta .
"</body></html>");
}
else
return new Response("No s'han trobat equips");
}
}
?>

