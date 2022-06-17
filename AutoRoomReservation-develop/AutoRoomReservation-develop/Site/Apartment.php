<?php

class Apartment 
{

    private $id;
    public function getId() {
         return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    private $city;
    public function getCity() {
         return $this->city;
    }
    public function setcity($city) {
        $this->city = $city;
    }

    private $name;
    public function getName() {
         return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    private $street;
    public function getStreet() {
         return $this->street;
    }
    public function setStreet($street) {
        $this->street = $street;
    }
    
    private $zipCode;
    public function getZipCode() {
         return $this->zipCode;
    }
    
    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    public function toJSON(){
        // Retourne l'objet au format JSON
        return json_encode(get_object_vars($this));
    }

}