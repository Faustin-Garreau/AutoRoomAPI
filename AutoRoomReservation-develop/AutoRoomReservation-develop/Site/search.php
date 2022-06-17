<?php 
require('config.php');

if(!empty($_GET)) {
    if((isset($_GET['ville']) && !empty($_GET['ville']))) {
        $ville = htmlspecialchars($_GET['ville']);

        $url = 'http://localhost:5287/Room/Search?ville=' . $ville;
        $result = executeRequest($url,'',false);
        if ($result->Success != true) {
            $_SESSION['erreur'] =  "Il n'y a aucune chambre pour cette ville";
            header('Location: accueil.php');
        }else {
            $chambres = $result->Content;
        }
    }else {
        $_SESSION['erreur'] =  "Veuillez remplir tout les champs du formulaire";
        header('Location: accueil.php');
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
    <h1 class="text-center mt-24">Reservation</h1>
    <div class="flex justify-evenly bloc_chambre flex-wrap">
        <?php foreach($chambres as $chambre) { ?>
        <div class="flex chambre flex-col items-center bg-white rounded-lg border shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex flex-col p-4 leading-normal">
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= 'Nom : Appartement' ?></p>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= 'Prix : ' . $chambre->Price . ' €'; ?></p>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= 'Taille : ' . $chambre->Area; ?></p>
                <form action="/detail.php?" type="GET">
                    <input type="hidden" name="ville" value="<?= $ville ?>">
                <button name="Id" value="<?= $chambre->Id;?>" type="submit" class="font-medium p-2 md:p-4 button_search uppercase w-full">Details</button>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
<?php } ?>
</html>