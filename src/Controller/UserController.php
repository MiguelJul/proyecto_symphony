<?php
namespace App\Controller;
use App\Entity\Usuari;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Jenssegers\Date\Date;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class UserController extends AbstractController
{
    
    #[Route('/usuari' ,name:'usuari')]
    public function inici(ManagerRegistry $doctrine)
    {$dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $repositori = $doctrine->getRepository(Usuari::class);
        $usuaris = $repositori->findAll();
        return $this->render('usuari.html.twig',array('usuaris'=>$usuaris,"error"=>$error,"fechacompleta"=>$fechacompleta));
    }
    #[Route('/usuari/nou/' ,name:'nou_usuari')]
    public function nou(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher)
    {$dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $usuari = new Usuari();
        $formulari = $this->createFormBuilder($usuari)
        ->add('username', TextType::class)
        ->add('roles', HiddenType::class,['mapped' => false,'required' => false])
        ->add('password', PasswordType::class)
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $usuari->setUsername($formulari->get('username')->getData());
            $password=$formulari->get('password')->getData();
            
            $hashedPassword = $passwordHasher->hashPassword($usuari,$password);
            $usuari->setPassword($hashedPassword);
            $usuari->setRoles(["ROLE_USER"]);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($usuari);
            try{
            $entityManager->flush();
            return $this->redirectToRoute('usuari');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('nou_usuari.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error,"fechacompleta"=>$fechacompleta));

            }

        }else{
            return $this->render('nou_usuari.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error,"fechacompleta"=>$fechacompleta));
        }
    }

    #[Route('/usuari/editar/{codi}' ,name:'editar_usuari')]
    public function editarUsuari(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher,$codi=1)
    {$dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $repositori = $doctrine->getRepository(Usuari::class);
        $usuari = $repositori->find($codi);
        $formulari = $this->createFormBuilder($usuari)
        ->add('username', TextType::class)
        ->add('roles', HiddenType::class,['mapped' => false,'required' => false])
        ->add('password', PasswordType::class, ['mapped' => false,'required' => false])
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            $usuari->setUsername($formulari->get('username')->getData());
            $password=$formulari->get('password')->getData();
            if($password!=null){
                $hashedPassword = $passwordHasher->hashPassword($usuari,$password);
                $usuari->setPassword($hashedPassword);
            }
            else{
                $usuari->setPassword($usuari->getPassword());
            }
            $usuari->setRoles($usuari->getRoles());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($usuari);
            try{
            $entityManager->flush();
            return $this->redirectToRoute('usuari');

            }catch (\Exception $e) {

                $error=$e->getMessage();
        return $this->render('editar_usuari.html.twig', array(
            'formulari' => $formulari->createView(), "error"=>$error,"fechacompleta"=>$fechacompleta));

            }

        }else{
            return $this->render('editar_usuari.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error,"fechacompleta"=>$fechacompleta));
        }
    }

    #[Route('/usuari/elimiar/{codi}' ,name:'eliminar_usuari')]
    public function eliminarUsuari(ManagerRegistry $doctrine,$codi=1)
    {  $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $entityManager = $doctrine->getManager();
        $error=null;
        $repositori = $doctrine->getRepository(Usuari::class);
        $usuari = $repositori->find($codi);
        $entityManager->remove($usuari);
        try{
            $usuaris = $repositori->findAll();
            $entityManager->flush();
            return $this->redirectToRoute('usuari');
            }
        catch (\Exception $e) {
            $error=$e->getMessage();
            return $this->render('usuari.html.twig', array(
                'usuaris' => $usuaris, "error"=>$error,"fechacompleta"=>$fechacompleta));
            }
        }
    }
    
    
    ?>