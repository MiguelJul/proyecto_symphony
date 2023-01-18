<?php
namespace App\Controller;
use App\Entity\Equip;
use App\Entity\Membre;
use App\Service\ServeiDadesEquips;  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EquipNouType;
use App\Form\EquipEditarType;

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
    $repositori2 = $doctrine->getRepository(Membre::class);
    $equip = $repositori->find($codi);
    $membres=$repositori2->findAll();
    /*foreach ($repositori2 as $membre){
        echo ($membre);
        if($membre.getEquip()==$equip.getNom())
        array_push($membres,$membre);
    }*/
    /*
    $resultat = array_filter($this->equips,
    function($equip) use ($codi)
    {
    return $equip["codi"] == $codi;
    });*/
    
    if ($equip!=null){
    return $this->render('equip.html.twig', array('equip'=>$equip,'codi'=>$codi,'membres'=>$membres));
    }
    else
    return $this->render('equip.html.twig', array(
        'equip' => NULL,'codi'=>NULL,'membres'=>NULL));
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
    $equip->setImatge("Green/assets/img/equips/sith.jpg");
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
    $equip1->setImatge("Green/assets/img/equips/rojo.jpg");
    $entityManager->persist($equip1);

    $equip2 = new equip();
    $equip2->setNom("Equip Blau");
    $equip2->setCicle("DAM");
    $equip2->setCurs("22/23");
    $equip2->setNota(4.4);
    $equip2->setImatge("Green/assets/img/equips/azul.jpg");
    $entityManager->persist($equip2);

    $equip3 = new equip();
    $equip3->setNom("Equip Groc");
    $equip3->setCicle("ASIR");
    $equip3->setCurs("22/23");
    $equip3->setNota(7.8);
    $equip3->setImatge("Green/assets/img/equips/verde.jpeg");
    $entityManager->persist($equip3);

    $equip4 = new equip();
    $equip4->setNom("Equip Negre");
    $equip4->setCicle("ASIX");
    $equip4->setCurs("22/23");
    $equip4->setNota(3.7);
    $equip4->setImatge("Green/assets/img/equips/negro.jpg");
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

#[Route('/equip/nou/' ,name:'nou_equip')]
    public function nou(ManagerRegistry $doctrine, Request $request)
    {
        $error=null;
        $equip = new Equip();
        $formulari = $this->createForm(EquipNouType::class, $equip);
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatge')->getData();
            if ($fitxer) { // si s’ha indicat un fitxer al formulari
            $nomFitxer = "Green/assets/img/equips/".$fitxer->getClientOriginalName();
            //ruta a la carpeta de les imatges d’equips, relativa a index.php
            //aquest directori ha de tindre permisos d’escriptura
            $directori =
            $this->getParameter('kernel.project_dir')."/public/Green/assets/img/equips";
            try {
            $fitxer->move($directori,$nomFitxer);
            } catch (FileException $e) {

                $error=$e->getMessage();
        return $this->render('nou_equip.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error));

            }
            $equip->setImatge($nomFitxer); // valor del camp imatge
            } else {//no hi ha fitxer, imatge per defecte
            $equip->setImatge('Green/assets/img/equips/equipPerDefecte.jpg');
            }

            //hem d’assignar els camps de l’equip 1 a 1
            $equip->setNom($formulari->get('nom')->getData());
            $equip->setCicle($formulari->get('cicle')->getData());
            $equip->setCurs($formulari->get('curs')->getData());
            $equip->setNota($formulari->get('nota')->getData());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($equip);
            try{
            $entityManager->flush();
            return $this->redirectToRoute('inici');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('nou_equip.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error));

            }

        }else{
            return $this->render('nou_equip.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error));
        }
    }

    #[Route('/equip/editar/{codi}' ,name:'edicio_equip', requirements: ['codi' => '\d+'])]
    public function edicioEquip(ManagerRegistry $doctrine, Request $request, $codi=0)
    {
        $error=null;
        $repositori = $doctrine->getRepository(Equip::class);
        $equip = $repositori->find($codi);
        $formulari = $this->createForm(EquipEditarType::class, $equip);
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatge')->getData();
            if ($fitxer) { // si s’ha indicat un fitxer al formulari
            $nomFitxer = "Green/assets/img/equips/".$fitxer->getClientOriginalName();
            //ruta a la carpeta de les imatges d’equips, relativa a index.php
            //aquest directori ha de tindre permisos d’escriptura
            $directori =
            $this->getParameter('kernel.project_dir')."/public/Green/assets/img/equips";
            if($equip->getImatge()!="Green/assets/img/equips/equipPerDefecte.jpg")
            unlink($equip->getImatge());

            try {
            $fitxer->move($directori,$nomFitxer);
            } catch (FileException $e) {

                $error=$e->getMessage();
        return $this->render('editar_equip.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error, "imatge"=>$equip->getImatge()));

            }
            $equip->setImatge($nomFitxer); // valor del camp imatge
            } 

            //hem d’assignar els camps de l’equip 1 a 1
            $equip->setNom($formulari->get('nom')->getData());
            $equip->setCicle($formulari->get('cicle')->getData());
            $equip->setCurs($formulari->get('curs')->getData());
            $equip->setNota($formulari->get('nota')->getData());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($equip);
            try{
            $entityManager->flush();
            return $this->redirectToRoute('inici');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('editar_equip.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error,"imatge"=>$equip->getImatge()));

            }

        }else{
            return $this->render('editar_equip.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error,"imatge"=>$equip->getImatge()));
        }
    }
}
?>

