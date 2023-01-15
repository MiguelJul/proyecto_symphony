<?php
namespace App\Controller;
use App\Entity\Membre;
use App\Entity\Equip; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    {
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
        'membre' => $membre, "error"=>null));
    } catch (\Exception $e) {
    $error=$e->getMessage();
    return $this->render('inserir_membre.html.twig', array(
            'membre' => $membre, "error"=>$error));
    }

}

#[Route('/membre/nou/' ,name:'nou_membre')]
    public function nou(ManagerRegistry $doctrine, Request $request)
    {
        $error=null;
        $equip = new Membre();
        $formulari = $this->createFormBuilder($equip)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class)
        ->add('dataNaiximent', DateType::class)
        ->add('imatgePerfil',FileType::class,array('required' => false))
        ->add('equip', EntityType::class, array('class' =>
        Equip::class,'choice_label' => 'nom',))
        ->add('nota', NUmberType::class)
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
            'formulari' => $formulari->createView(), "error"=>$error));

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
        return $this->render('nou_membre.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error));

            }

        }else{
            return $this->render('nou_membre.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error));
        }
    }

    #[Route('/membre/editar/{codi}' ,name:'edicio_membre', requirements: ['codi' => '\d+'])]
    public function edicioEquip(ManagerRegistry $doctrine, Request $request, $codi=0)
    {
        $error=null;
        $repositori = $doctrine->getRepository(Membre::class);
        $equip = $repositori->find($codi);
        $formulari = $this->createFormBuilder($equip)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class)
        ->add('dataNaiximent', DateType::class)
        ->add('imatgePerfil',FileType::class,['mapped' => false,'required' => false])
        ->add('equip', EntityType::class, array('class' =>
        Equip::class,'choice_label' => 'nom',))
        ->add('nota', NUmberType::class)
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
            'formulari' => $formulari->createView(), "error"=>$error, "imatge"=>$equip->getImatgePerfil()));

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
            'formulari' => $formulari->createView(), "error"=>$error, "imatge"=>$equip->getImatgePerfil()));

            }

        }else{
            return $this->render('editar_membre.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error, "imatge"=>$equip->getImatgePerfil()));
        }
    }

}