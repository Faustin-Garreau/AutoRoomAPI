<?php

function executeRequest($url,$data,$post){

    // créer un objet curl
    $ch = curl_init();

    // rajoute l'option URL
    curl_setopt($ch,CURLOPT_URL, $url);

    // rajoute l'option POST à la requete
    if (isset($post) && !empty($post)) {
        curl_setopt($ch,CURLOPT_POST, $post);
    }

    // rajoute le contenu de la requete
    if (isset($data) && !empty($data)) {
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    }
    // on sait pas
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    
    $headers = array();
    if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
        array_push($headers,"User-Id:".$_SESSION["id"]);
    }
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    

    $res = curl_exec($ch);

    if ($res==false) {
	    return (object)array("Success"=>false, "Error"=>"Erreur lors de l'appel de l'api");
    }
    // retourne sous forme d'objet php les valeurs retournées en json
    return json_decode($res);
}