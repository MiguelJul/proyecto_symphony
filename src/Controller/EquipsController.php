<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ContacteController
{
private $equips = array(
    array("codi" => "1", "nom" => "Equip Roig", "cicle" =>"DAW",
    "curs" =>"22/23", "membres" =>
    array("Elena","Vicent","Joan","Maria")),
    array("codi" => "2", "nom" => "Equip Blau", "cicle" =>"DAM",
    "curs" =>"22/23", "membres" =>
    array("Carlos","Irene","Lorena","Ricardo")),
    array("codi" => "3", "nom" => "Equip Groc", "cicle" =>"ASIR",
    "curs" =>"22/23", "membres" =>
    array("Toni","Ximo","Paula","Laura")),
    array("codi" => "4", "nom" => "Equip Negre", "cicle" =>"ASIX",
    "curs" =>"22/23", "membres" =>
    array("Marc","Sara","Roman","Aitor"))
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
{
 
$resposta = "";
$resultat = array_shift($resultat); #torna el primer element
$membres="";
foreach($resultat["membres"] as $membre)
$membres.=$membre.", ";
$resposta .= "<ul><li>" . $resultat["nom"] . "</li>" .
"<li>" . $resultat["cicle"] . "</li>" .
"<li>" . $resultat["curs"] . "</li>".
"<li>" . $membres . "</li>".
"</ul>";
return new Response("<html><body>$resposta</body></html>");
}
else
return new Response("No s’ha trobat l’equip: ".$codi );
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

