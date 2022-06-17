<?php 
require('config.php');
require('Apartment.php');


if (!isset($_SESSION["id"]) ) {
    $_SESSION["erreur"] = "Vous devez être connecté pour accéder à cette page";
    header("Location: index.php");
    die;
}
if ($_SESSION["admin"]==false) {
    $_SESSION["erreur"] = "Vous devez être un admin pour accéder à cette page";
    header("Location: accueil.php");
    die;
}

if(!empty($_GET)) {
    if((isset($_GET['id']) && !empty($_GET['id']))) {
        $id = $_GET['id'];

        $url = 'http://localhost:5287/Apartment/Get?Id='.$_GET['id'];
        $resultGet = executeRequest($url,'',false);
        
        if ($resultGet->Success == false) {
            $_SESSION['erreur'] =  $result->Error;
            header('Location: apart_liste.php');
            die;
        }else {
            $apart = $resultGet->Content;

        }

        
    }
}

if (!empty($_POST)) {
  if((isset($_POST['City']) && !empty($_POST['City'])) && 
    (isset($_POST['zipCode']) && !empty($_POST['zipCode'])) &&
    (isset($_POST['nom']) && !empty($_POST['nom'])) &&
    (isset($_POST['street']) && !empty($_POST['street']))){
      
      $city = htmlspecialchars($_POST['City']);
      $zipCode = htmlspecialchars($_POST['zipCode']);
      $nom = htmlspecialchars($_POST['nom']);
      $street = htmlspecialchars($_POST['street']);
      $id = $_POST['id'];
      
      $apartment = new Apartment;
      $apartment->setName($nom);
      $apartment->setId($id);
      $apartment->setcity($city);
      $apartment->setZipCode($zipCode);
      $apartment->setStreet($street);

      $url = 'http://localhost:5287/Apartment/Update';

      $result = executeRequest($url,$apartment->toJson(),true); 
      var_dump($result);

      if ($result->Success==true) {
          header('Location: /apart_liste.php');
      }else{
          $_SESSION["erreur"] = $result->Error;
          header('Location: /apart_update.php?id='.$id);

      }

    }else {
      $_SESSION['erreur'] = "Veuillez remplir tout les champs du formulaire";
  }
  /*$apartment = new Apartment;
  $apartment->setId($resultGet->Content->id);
  $apartment->setName($resultGet->Content->name);
  $apartment->setcity($resultGet->Content->city);
  $apartment->setZipCode($resultGet->Content->zipCode);
  $apartment->setStreet($resultGet->Content->street);

  $url = 'http://localhost:5287/Apartment/Update';
  $result = executeRequest($url,$apartment->toJson(),true);
  var_dump($result);
  die;

  if ($result->Success != true) {
      $_SESSION['erreur'] =  $result->Error;
      header('Location: apart_liste.php');
  }else {
      header('Location: apart_liste.php');
  }*/
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un appartement</title>
</head>

<body>
<div class="login">
<h1 class="text-center titre_login mt-11">Modifier un appartement</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12" action="/apart_edit.php" method="POST">

      <input value="<?=$apart->id?>" type="hidden" id="id" name="id" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Id"/>

      <div class="flex items-center text-lg mb-6">
        <input value="<?=$apart->name?>" type="text" id="nom" name="nom" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nom de l'appartment"/>
      </div>
      <div class="flex items-center text-lg mb-6">
        <input value="<?=$apart->zipCode?>" type="text" id="zipCode" name="zipCode" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Code postal"/>
      </div>
      <div class="flex items-center text-lg mb-6">
        <input value="<?=$apart->city?>" type="text" id="City" name="City" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Ville"/>
      </div>
      <div class="flex items-center text-lg mb-6">
        <input value="<?=$apart->street?>" type="text" id="Street" name="street" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Adresse"/>
      </div>
      <?php if(!empty($_SESSION['erreur'])){?> 
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3">
            <strong class="font-bold"><?php echo $_SESSION['erreur']; ?></strong>
        </div>
        <?php }?>
      <div class="flex items-center justify-between flex-row-reverse flex-wrap-reverse">
        <button type="submit" class="font-medium p-2 md:p-4 button_register uppercase w-full">Modifier</button>
      </div>
    </form>
  </div>
 </div>
 </div>
</body>

</html>

<?php $_SESSION['erreur'] = "";  ?>
