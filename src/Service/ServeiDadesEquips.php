<?php
namespace App\Service;
class ServeiDadesEquips
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
public function get()
{
return $this->equips;
}
}
?>