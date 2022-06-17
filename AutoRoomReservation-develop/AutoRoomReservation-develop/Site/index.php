<?php 
require('config.php');
require('User.php');

if(!empty($_POST)) {
  if((isset($_POST['login_email']) && !empty($_POST['login_email'])) && 
     (isset($_POST['login_password']) && !empty($_POST['login_password']))){

      $login_email = htmlspecialchars($_POST['login_email']);
      $login_password = sha1($_POST['login_password']);
      if (!filter_var($login_email, FILTER_VALIDATE_EMAIL)) {
          $_SESSION['erreur'] = 'Email invalide !';
      }else {
        $user = new User;
        $user->setEmail($login_email);
        $user->setPassword($login_password);

        $url = 'http://localhost:5287/User/Login';
        $result = executeRequest($url,$user->toJson(),true);
        /*if ($result->Success==true) {
        header('Location: /index.php');
        }else{
        $_SESSION["erreur"] = $result->Error;
        }*/

        if($result->Success == true ) {
          $_SESSION['id'] = $result->Content->Id;
          $_SESSION['email'] = $result->Content->email;
          $_SESSION['password'] = $result->Content->password;
          $_SESSION['nom'] = $result->Content->lastname;
          $_SESSION['prenom'] = $result->Content->firstname;
          $_SESSION['admin'] = $result->Content->admin;
          header('Location: accueil.php');
        }else {
            $_SESSION['erreur'] = $result->Error;
          }
      }
  }else {
    $_SESSION['erreur'] = "Veuillez remplir tout les champs du formulaire";
}



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
    <title>Login</title>
</head>

<body>
<div class="login">
<h1 class="text-center login_titre mt-48">Se connecter</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12 md:p-24" action="/" method="POST">
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <svg class="absolute ml-3" width="24" viewBox="0 0 24 24">
          <path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
        </svg>
        <input type="text" name="login_email" id="username" class="bg-gray-200 pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Email"/>
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <svg class="absolute ml-3" viewBox="0 0 24 24" width="24">
          <path d="m18.75 9h-.75v-3c0-3.309-2.691-6-6-6s-6 2.691-6 6v3h-.75c-1.24 0-2.25 1.009-2.25 2.25v10.5c0 1.241 1.01 2.25 2.25 2.25h13.5c1.24 0 2.25-1.009 2.25-2.25v-10.5c0-1.241-1.01-2.25-2.25-2.25zm-10.75-3c0-2.206 1.794-4 4-4s4 1.794 4 4v3h-8zm5 10.722v2.278c0 .552-.447 1-1 1s-1-.448-1-1v-2.278c-.595-.347-1-.985-1-1.722 0-1.103.897-2 2-2s2 .897 2 2c0 .737-.405 1.375-1 1.722z"/>
        </svg>
        <input type="password" name="login_password" id="password" class="bg-gray-200 pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Mot de passe" />
      </div>
      <?php if(!empty($_SESSION['erreur'])){?> 
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3">
            <strong class="font-bold"><?php echo $_SESSION['erreur']; ?></strong>
        </div>
        <?php }?>
      <div class="flex items-center justify-between flex-row-reverse flex-wrap-reverse">
      <button type="submit" class="font-medium p-2 md:p-4 button_login uppercase w-full">Connexion</button>
      <a href="register.php" class="font-medium">Pas de compte ? Cliquez ici</button>
      </div>
    </form>
  </div>
 </div>
 </div>
</body>

</html>

<?php $_SESSION['erreur'] = "";  ?>