<?php 

require('config.php');

if (!isset($_SESSION["id"]) ) {
    $_SESSION["erreur"] = "Vous devez être connecté pour accéder à cette page";
    header("Location: index.php");
}
if ($_SESSION["admin"]==false) {
    $_SESSION["erreur"] = "Vous devez être un admin pour accéder à cette page";
    header("Location: accueil.php");
}

$url = 'http://localhost:5287/Apartment/GetAll';

$result = executeRequest($url,"",false);
//var_dump($result);
if ($result->Success==true) {
    $aparts = $result->Content;
}else{
    $_SESSION["erreur"] = $result->Error;
    header("Locatin: admin.php");
}
//var_dump($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<?php if (!empty($_SESSION['id'])) { ?>
<body>
    <header>
    <nav class="flex items-center justify-between bg-white px-12">
    <img class="image_navbar" src="/images/lit.png" alt="lit">
    <ul class="flex">
        <li class="mr-6">
            <a class="text-lg link_navbar" href="accueil.php">Accueil</a>
        </li>
        <?php
        if (isset($_SESSION["id"]) && $_SESSION["admin"]==true) {
        ?>
            <li class="mr-6">
                <a class="text-lg link_navbar" href="admin.php">Admin</a>
            </li>
        <?php 
        }
        ?>
        <li class="mr-6">
            <a class="text-lg link_navbar" href="deconnexion.php">Se deconnecter</a>
        </li>
    </ul>
    </nav>
    </header>
    <h1 class="text-center mt-24">Admin</h1>
    <a href="./apart_add.php" class="font-medium p-2 md:p-4 button_login uppercase text-center cursor-pointer" style="width:100%!important;">Ajouter un appartement</a>
    <div class="flex justify-around items-center mt-11 flex-col ">
        <?php foreach ($aparts as $apart) {
        ?>
            <div class="flex flex-col items-center bg-white rounded-lg border shadow-md md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <div class="flex flex-col justify-between p-4 leading-normal">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Nom : <?=$apart->name?></h5>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Adresse : <?=$apart->street?></h5>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Ville : <?=$apart->city?>  / Code postal : <?=$apart->zipCode?> </h5>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Chambres : <?= count($apart->Rooms)?> </h5>
                    <a href="./apart_edit.php?id=<?=$apart->id?>">Editer</a>
                    <a href="./apart_delete.php?id=<?=$apart->id?>">Supprimer</a>
                </div>
            </div>
        <?php }?>
    </div>
</body>
<?php }else {
    $_SESSION['erreur'] = "Veuillez vous connecter avant d'acceder au site internet";
    header('Location: index.php');
    die;
} ?>
</html>

<style>   
body {
    background-color: #272838;
}

h1 {
    font-weight: bold;
    color : #F8E2CA;
    font-size: 60px;
}

.titre_acceuil {
    margin-top: 100px;
}

.image_navbar {
    width: 100px;
}

.button_acceuil {
    background-color: #272838; 
    color : #F8E2CA;
    width : 50%;
}
.search {
    width: 50%;
}

#search_appartment {
    width: 70%;
}

@media screen and (max-width: 1024px)
{
    ul
    {
         display:flex !important;
        flex-direction: column !important;
    }

    .search {
    width: 80%;
}
}


</style>