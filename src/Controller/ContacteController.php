<?php
namespace App\Controller;
use Jenssegers\Date\Date;
use App\Entity\Contacte;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ContacteController extends AbstractController
{
    
    #[Route('/contacte' ,name:'contacte')]
    public function inici(ManagerRegistry $doctrine,Request $request, MailerInterface $mailer)
    {   $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
        $meses=["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
        Date::setLocale('ca_ES');
        $hui = Date::now();
        $huitexto=$hui->format('w/d/m/Y');
        $huitexto=explode("/",$huitexto);
        $fecha=[$dias[$huitexto[0]-1],$meses[$huitexto[2]-1]];
        $fechacompleta=$fecha[0].", ".$huitexto[1]." de ".$fecha[1].", carregat a les ".$hui->format("H:i:s");

        $error=null;
        $equip = new Contacte();
        $formulari = $this->createFormBuilder($equip)
        ->add('correu', TextType::class)
        ->add('assumpte', TextType::class)
        ->add('missatge', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        $formulari->handleRequest($request);
        if ($formulari->isSubmitted() && $formulari->isValid()) {
            echo($formulari->get("correu")->getData());
            $email = (new Email())
            ->from('migjulcal@alu.edu.gva.es')
            ->to($formulari->get("correu")->getData())
            ->subject('Document de contacte')
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
                ">Benvingut: '.$formulari->get("correu")->getData().'</h1>
                <div style="position: relative;
                width: 80%;
                border: solid black 1px;
                margin: 10px auto;
                text-align:center;
                padding:10px;
                background-color:olive;"><p style="margin:20px;border:2px solid black; background-color:#f2e7c3;"> Assumpte:'.$formulari->get("assumpte")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;">Missatge:'.$formulari->get("missatge")->getData().'</p>
                <p style="margin:20px;border:2px solid black; background-color:#f2e7c3;"><img src="https://media.revistagq.com/photos/62a0a996223a33e985e4d59a/16:9/w_2560%2Cc_limit/1072434_110615-cc-Darth-Vader-Thumb.jpg" width="300px" height="180px" alt="" /></p>
                ');
            try{
                $mailer->send($email);
                return $this->redirectToRoute('inici');
    
                }catch (\Exception $e) {
    
                    $error=$e->getMessage();
            return $this->render('contacte.html.twig', array(
                'formulari' => $formulari->createView(), "error"=>$error,"fechacompleta"=>$fechacompleta));
    
                }
        }
        else{
            return $this->render('contacte.html.twig',
            array('formulari' => $formulari->createView(),"error"=>$error,"fechacompleta"=>$fechacompleta));
        }
    }

}