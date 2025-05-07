<?php
namespace App\DTO;

class EnterpriseDto{
    public int $id;
    public int $ownerId;
    public string $name;
    public string $nif;
    public int $employeCount;
    public string $ville;
    public string $pays;
    public string $phone;
    public string $adresse;
    public string $type;
    public string $clientCode;
    
    public function __construct(
        int $id = 0,
        int $ownerId = 0,
        string $name = '',
        string $nif = '',
        int $employeCount = 0,
        string $ville = '',
        string $pays = '',
        string $phone = '',
        string $adresse = ''
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->nif = $nif;
        $this->employeCount = $employeCount;
        $this->ville = $ville;
        $this->pays = $pays;
        $this->phone = $phone;
        $this->adresse = $adresse;
    }
    

}