<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Jenssegers\Date\Date;
use App\Entity\Membre;
use App\Entity\Equip; 
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
class MembresController extends AbstractController
{

#[Route('/membre/inserir' ,name:'inserir_membre', requirements: ['codi' => '\d+'])]
    public function inserirmembre(ManagerRegistry $doctrine)
    {   $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $entityManager = $doctrine->getManager();
        $repository=$doctrine->getRepository(Equip::class);
        $equip = $repository->findOneBy(["nom"=>"Simarrets"]);
        $membre = new membre();
        $membre->setNom("Sarah");
        $membre->setCognoms("Connor");
        $membre->setImatgePerfil("sarahconnor.jpg");
        $membre->setEmail("sarahconnor@skynet.com");
        $membre->setNota(9.7);
        $fecha=new \DateTime("11/29/1963");
        $membre->setDataNaiximent($fecha);
        $membre->setEquip($equip);
        $entityManager->persist($membre);
        try{
    $entityManager->flush();
    return $this->render('inserir_membre.html.twig', array(
        'membre' => $membre, "error"=>null,"fechacompleta"=>$fechacompleta));
    } catch (\Exception $e) {
    $error=$e->getMessage();
    return $this->render('inserir_membre.html.twig', array(
            'membre' => $membre, "error"=>$error,"fechacompleta"=>$fechacompleta));
    }

}

#[Route('/membre/nou/' ,name:'nou_membre')]
    public function nou(ManagerRegistry $doctrine, Request $request, MailerInterface $mailer)
    {   $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $equip = new Membre();
        $formulari = $this->createFormBuilder($equip)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class)
        ->add('dataNaiximent', DateType::class, ['years'=>range(1920,2022)])
        ->add('imatgePerfil',FileType::class,array('required' => false))
        ->add('equip', EntityType::class, array('class' =>
        Equip::class,'choice_label' => 'nom',))
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatgePerfil')->getData();
            if ($fitxer) { // si s’ha indicat un fitxer al formulari
            $nomFitxer = $fitxer->getClientOriginalName();
            //ruta a la carpeta de les imatges d’equips, relativa a index.php
            //aquest directori ha de tindre permisos d’escriptura
            $directori =
            $this->getParameter('kernel.project_dir')."/public/Green/assets/img/membres";
            try {
            $fitxer->move($directori,$nomFitxer);
            } catch (FileException $e) {

                $error=$e->getMessage();
        return $this->render('nou_membre.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error,"fechacompleta"=>$fechacompleta));

            }
            $equip->setImatgePerfil($nomFitxer); // valor del camp imatge
            } else {//no hi ha fitxer, imatge per defecte
            $equip->setImatgePerfil('membrePerDefecte.jpg');
            }

            //hem d’assignar els camps de l’equip 1 a 1
            $equip->setNom($formulari->get('nom')->getData());
            $equip->setCognoms($formulari->get('cognoms')->getData());
            $equip->setEmail($formulari->get('email')->getData());
            $equip->setDataNaiximent($formulari->get('dataNaiximent')->getData());
            $equip->setEquip($formulari->get('equip')->getData());
            $equip->setNota($formulari->get('nota')->getData());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($equip);
            $email = (new Email())
            ->from('migjulcal@alu.edu.gva.es')
            ->to('migueljulia123@gmail.com')
            ->subject('Creació de membre')
            ->text('Membre creat correctament')
            ->html('
            <h1 id="titolInici" style="
                width: 50%;
                color: red;
                border: solid red 1px;
                margin: 10px auto;
                text-align:center;
                padding:10px;
                background-color:white;
                ">Membre creat correctament</h1>
                <div style="position: relative;
                width: 80%;
                border: solid black 1px;
                margin: 10px auto;
                text-align:center;
                padding:10px;
                background-color:olive;"><p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Nom :'.$formulari->get("nom")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Cognoms :'.$formulari->get("cognoms")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Equip :'.$formulari->get("equip")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Email :'.$formulari->get("email")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Nota :'.$formulari->get("nota")->getData().'</p>
                <img src="https://media.revistagq.com/photos/62a0a996223a33e985e4d59a/16:9/w_2560%2Cc_limit/1072434_110615-cc-Darth-Vader-Thumb.jpg" width="300px" height="180px" alt="" />
                ');
                $email2 = (new Email())
            ->from('migjulcal@alu.edu.gva.es')
            ->to('migjulcal@alu.edu.gva.es')
            ->subject('Creació de membre')
            ->text('Membre creat correctament')
            ->html('
            <h1 id="titolInici" style="
                width: 50%;
                color: red;
                border: solid red 1px;
                margin: 10px auto;
                text-align:center;
                padding:10px;
                background-color:white;
                ">Membre creat correctament</h1>
                <div style="position: relative;
                width: 80%;
                border: solid black 1px;
                margin: 10px auto;
                text-align:center;
                padding:10px;
                background-color:olive;"><p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Nom :'.$formulari->get("nom")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Cognoms :'.$formulari->get("cognoms")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Equip :'.$formulari->get("equip")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Email :'.$formulari->get("email")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Nota :'.$formulari->get("nota")->getData().'</p>
                <img src="https://media.revistagq.com/photos/62a0a996223a33e985e4d59a/16:9/w_2560%2Cc_limit/1072434_110615-cc-Darth-Vader-Thumb.jpg" width="300px" height="180px" alt="" />
                ');
            try{
            $entityManager->flush();
            $mailer->send($email);
            $mailer->send($email2);
            return $this->redirectToRoute('inici');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('nou_membre.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error,"fechacompleta"=>$fechacompleta));

            }

        }else{
            return $this->render('nou_membre.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error,"fechacompleta"=>$fechacompleta));
        }
    }

    #[Route('/membre/editar/{codi}' ,name:'edicio_membre', requirements: ['codi' => '\d+'])]
    public function edicioEquip(ManagerRegistry $doctrine, Request $request, $codi=1)
    {   $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $repositori = $doctrine->getRepository(Membre::class);
        $equip = $repositori->find($codi);
        $formulari = $this->createFormBuilder($equip)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class)
        ->add('dataNaiximent', DateType::class, ['years'=>range(1920,2022)])
        ->add('imatgePerfil',FileType::class,['mapped' => false,'required' => false])
        ->add('equip', EntityType::class, array('class' =>
        Equip::class,'choice_label' => 'nom',))
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatgePerfil')->getData();
            if ($fitxer) { // si s’ha indicat un fitxer al formulari
            $nomFitxer = $fitxer->getClientOriginalName();
            //ruta a la carpeta de les imatges d’equips, relativa a index.php
            //aquest directori ha de tindre permisos d’escriptura
            $directori =
            $this->getParameter('kernel.project_dir')."/public/Green/assets/img/membres";
            if($equip->getImatgePerfil()!="membrePerDefecte.jpg")
            unlink("Green/assets/img/membres/".$equip->getImatgePerfil());
            try {
            $fitxer->move($directori,$nomFitxer);
            } catch (FileException $e) {

                $error=$e->getMessage();
        return $this->render('editar_membre.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error, "imatge"=>$equip->getImatgePerfil(),"fechacompleta"=>$fechacompleta));

            }
            $equip->setImatgePerfil($nomFitxer); // valor del camp imatge
            } else {//no hi ha fitxer, imatge per defecte
            $equip->setImatgePerfil('membrePerDefecte.jpg');
            }

            //hem d’assignar els camps de l’equip 1 a 1
            $equip->setNom($formulari->get('nom')->getData());
            $equip->setCognoms($formulari->get('cognoms')->getData());
            $equip->setEmail($formulari->get('email')->getData());
            $equip->setDataNaiximent($formulari->get('dataNaiximent')->getData());
            $equip->setEquip($formulari->get('equip')->getData());
            $equip->setNota($formulari->get('nota')->getData());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($equip);
            try{
            $entityManager->flush();
            return $this->redirectToRoute('inici');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('editar_membre.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error, "imatge"=>$equip->getImatgePerfil(),"fechacompleta"=>$fechacompleta));

            }

        }else{
            return $this->render('editar_membre.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error, "imatge"=>$equip->getImatgePerfil(),"fechacompleta"=>$fechacompleta));
        }
    }

}