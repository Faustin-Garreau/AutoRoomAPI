<?php 

require('config.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <title>Accueil</title>
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
    <h1 class="text-center mt-24">Accueil</h1>
    <div class="flex justify-center items-center mt-11">
        <form action="/search.php?" class="bg-white search p-10 flex justify-center" method="GET">
        <input type="text" name="ville" placeholder="Rechercher" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none" id="search_appartment">
        <button type="submit" class="button_acceuil font-medium p-2 md:p-4 button_login uppercase">Valider</button>
        </form>
    </div>
    <?php if(!empty($_SESSION['erreur'])){?> 
        <div id="toast-danger" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 absolute" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </div>
    <div class="ml-3 text-sm font-normal"><?php echo $_SESSION['erreur']; ?></div>
</div>
        <?php }?>
</body>
<?php }else {
    $_SESSION['erreur'] = "Veuillez vous connecter avant d'acceder au site internet";
    header('Location: index.php');
    die;
} ?>
</html>


<?php $_SESSION['erreur'] = ""; ?>