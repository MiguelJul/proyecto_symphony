<?php
namespace App\Controller;
use App\Entity\Equip;
use App\Service\ServeiDadesEquips;  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class EquipsController extends AbstractController
{
/*private $equips = array(
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
); */
private $dadesEquips;
private $equips;
public function __construct($dadesEquips)
{
$this->equips = $dadesEquips->get();
}

#[Route('/equip/{codi}' ,name:'dades_equip', requirements: ['codi' => '\d+'])]
    public function fitxa(ManagerRegistry $doctrine,$codi=1)
    {
    $repositori = $doctrine->getRepository(Equip::class);
    $equip = $repositori->find($codi);
    /*
    $resultat = array_filter($this->equips,
    function($equip) use ($codi)
    {
    return $equip["codi"] == $codi;
    });*/

    if ($equip!=null)
    return $this->render('equip.html.twig', array('equip'=>$equip,'codi'=>$codi));
    else
    return $this->render('equip.html.twig', array(
        'equip' => NULL,'codi'=>NULL));
}


#[Route('/filrarnotes/nota/{nota}' ,name:'filtro_nota', requirements: ['codi' => '\d+'])]
    public function fitrar(ManagerRegistry $doctrine,$nota=0)
    {
        
        $repositori = $doctrine->getRepository(Equip::class);
        
        $equips = $repositori->findByNote($nota);
        $equipssort=rsort($equips);

    if ($equips!=null)
    return $this->render('filtrar_notes_equip.html.twig', array('equips'=>$equips));
    else
    return $this->render('filtrar_notes_equip.html.twig', array(
        'equips' => NULL));
}
/*#[Route('/equip/{text}' ,name:'buscar_equips')]
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
}*/

#[Route('/equip/inserir' ,name:'inserir_equip')]
public function inserir(ManagerRegistry $doctrine)
{
    $entityManager = $doctrine->getManager();
    $equip = new equip();
    $equip->setNom("Simarrets");
    $equip->setCicle("DAW");
    $equip->setCurs("22/23");
    $equip->setNota(9);
    $equip->setImatge("Green/assets/img/sith.jpg");
    $entityManager->persist($equip);
    try{
    $entityManager->flush();
    return $this->render('inserir_equip.html.twig', array(
        'equips' => $equip, "error"=>null));
    } catch (\Exception $e) {
        $error=$e->getMessage();
        return $this->render('inserir_equip.html.twig', array(
            'equips' => $equip, "error"=>$error));
    }
}

#[Route('/equip/inserirmultiple' ,name:'inserir_equips')]
public function inserirmultiple(ManagerRegistry $doctrine)
{
    $entityManager = $doctrine->getManager();
    $equip1 = new equip();
    $equip1->setNom("Equip Roig");
    $equip1->setCicle("DAW");
    $equip1->setCurs("22/23");
    $equip1->setNota(5.25);
    $equip1->setImatge("Green/assets/img/rojo.jpg");
    $entityManager->persist($equip1);

    $equip2 = new equip();
    $equip2->setNom("Equip Blau");
    $equip2->setCicle("DAM");
    $equip2->setCurs("22/23");
    $equip2->setNota(4.4);
    $equip2->setImatge("Green/assets/img/azul.jpg");
    $entityManager->persist($equip2);

    $equip3 = new equip();
    $equip3->setNom("Equip Groc");
    $equip3->setCicle("ASIR");
    $equip3->setCurs("22/23");
    $equip3->setNota(7.8);
    $equip3->setImatge("Green/assets/img/verde.jpeg");
    $entityManager->persist($equip3);

    $equip4 = new equip();
    $equip4->setNom("Equip Negre");
    $equip4->setCicle("ASIX");
    $equip4->setCurs("22/23");
    $equip4->setNota(3.7);
    $equip4->setImatge("Green/assets/img/negro.jpg");
    $entityManager->persist($equip4);

    $equips=array($equip1,$equip2,$equip3,$equip4);
    try{
    $entityManager->flush();
    return $this->render('inserir_equip_multiple.html.twig', array(
        'equips' => $equips, "error"=>null));
    } catch (\Exception $e) {
        $error=$e->getMessage();
        return $this->render('inserir_equip_multiple.html.twig', array(
            'equips' => $equips, "error"=>$error));
    }
}
}
?>

