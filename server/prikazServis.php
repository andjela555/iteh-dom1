<?php

class PrikazServis{

    private $broker;

    public function __construct($b){
        $this->broker=$b;
    }
    public function vratiSve(){
        $data=  $this->broker->ucitaj("select p.*, s.naziv as 'naziv_sale', f.naziv as 'naziv_filma', f.trajanje, f.ocena from prikaz p inner join sala s on(s.id=p.sala) inner join film f on (f.id=p.film_id)");
        $res=[];
        foreach($data as $element){
            $res[]=[
                "id"=>intval($element->id),
                "cena"=>doubleval($element->cena),
                "datum"=>$element->datum,
                "film"=>[
                    "id"=>intval($element->film_id),
                    "naziv"=>$element->naziv_filma,
                    "trajanje"=>intval($element->trajanje),
                    "ocena"=>doubleval($element->ocena)
                ],
                "sala"=>[
                    "id"=>intval($element->sala),
                    "naziv"=>$element->naziv_sale
                ]
            ];
        }
        return $res;
    }

    public function kreiraj($filmId,$salaId,$cena,$datum){
        
        $film= $this->broker->ucitaj("select * from film where id=".$filmId)[0];
        $trajanje=intval($film->trajanje);
        $prikazi= $this->broker->ucitaj("select p.*, f.trajanje from prikaz p inner join film f on(f.id=p.film_id) where sala=".$salaId);
        $vreme=strtotime($datum);
        $kraj=$vreme+$trajanje*60;
        foreach ($prikazi as $prikaz) {
            $vremePrikaza=strtotime($prikaz->datum);
            $krajPrikaza=$vremePrikaza+intval($prikaz->trajanje)*60;
            if($salaId==intval($prikaz->sala) &&
                (($vremePrikaza>=$vreme && $vremePrikaza<=$kraj)||($krajPrikaza>$vreme && $krajPrikaza<=$kraj))){
                throw new Exception("Sala je zauzeta u ovom terminu");
            }
          
        }
        $this->broker->upisi("insert into prikaz(film_id,sala,cena,datum) values(".$filmId.",".$salaId.",".$cena.",'".$datum."')");
    }
   
    public function obrisi($id){
        $this->broker->upisi("delete from prikaz where id=".$id);
    }
}

?>