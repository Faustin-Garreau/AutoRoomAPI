<?php 
require('config.php');

if(!empty($_GET)) {
    if((isset($_GET['Id']) && !empty($_GET['Id']))) {
        $id_chambre = $_GET['Id'];
        $get_ville_chambre = $_GET['ville'];

        $url = 'http://localhost:5287/Room/Get?Id=' . $id_chambre;
        $result = executeRequest($url,'',false);
        if ($result->Success != true) {
            $_SESSION['erreur'] =  "Il n'y a aucune chambre pour cette ville";
            header('Location: search.php?ville=' . $get_ville_chambre);
        }else {
            $chambre_detail = $result->Content;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
</head>
<body>
<?php if (!empty($_SESSION['id'])) { ?>
<body>
    <header>
    <nav class="flex items-center justify-between bg-white px-12">
    <img class="image_navbar" src="/images/lit.png" alt="lit">
    <ul class="flex">
        <li class="mr-6">
            <a class="text-lg link_navbar" href="acceuil.php">Acceuil</a>
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
    <h1 class="text-center mt-24">Detail de la chambre : <?= 'Appartement' ?></h1>
    <div class="flex justify-evenly bloc_chambre flex-wrap">
        <div class="flex chambre_detail flex-col items-center bg-white rounded-lg border shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex flex-col p-4 leading-normal">
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Nom : Appartement' ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Prix : ' . $chambre_detail->Price . ' €'; ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Taille : ' . $chambre_detail->Area; ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Place : ' . $chambre_detail->Place; ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Ville : ' . $chambre_detail->City; ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Rue : ' . $chambre_detail->Street; ?></p>
                <p class="mb-3 font-normal text_detail text-gray-700 dark:text-gray-400"><?= 'Code Postal : ' . $chambre_detail->ZipCode; ?></p>
                
            </div>
        </div>
    </div>
</body>
<?php } ?>
</html>