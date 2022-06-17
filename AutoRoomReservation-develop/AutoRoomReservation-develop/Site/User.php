<?php 

class User 
{
    private $firstname;
    public function getFirstName() {
         return $this->firstname;
    }
    public function setFirstName($firstname) {
        $this->firstname = $firstname;
    }

    private $lastname;
   public function getLastName() {
         return $this->lastname;
    }
    public function setLastName($lastname) {
        $this->lastname = $lastname;
    }

    private $email;
   public function getEmail() {
         return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    
    private $password;
   public function getPassword() {
         return $this->password;
    }
    public function setPassword($password) {
        $this->password = $password;
    }

    private $phone;
    public function getPhone() {
          return $this->phone;
     }
     public function setPhone($phone) {
         $this->phone = $phone;
     }

     private $birthdate;
    public function getBirthdate() {
          return $this->birthdate;
     }
     public function setBirthdate($birthdate) {
         $this->birthdate = $birthdate;
     }

     private $nationality;
     public function getNationality() {
           return $this->birthdate;
      }
      public function setNationality($nationality) {
          $this->nationality = $nationality;
      }

      private $admin;
      public function getAdmin() {
            return $this->admin;
       }
       public function setAdmin($admin) {
           $this->admin = $admin;
       }

        public function toJSON(){
            // Retourne l'objet au format JSON
            return json_encode(get_object_vars($this));
        }
}

?>