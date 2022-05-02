<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/inscription.css">
    <script src="../JS/JQuery.js"></script>
    <title>Inscription</title>
</head>
<body class="flex h-screen bg-green-100 justify-center items-center">
    <?php
        session_start();
        include "./cnx.php";
        $inscription = false;

        

        //Au deuxième chargement de la page
        if(isset($_POST["btnInscription"])){
            //Vérifier si le login est déja pris
            $login = $cnx->prepare("SELECT idEtudiant
            FROM etudiants
            WHERE login = :login");
            $login->bindValue(":login", $_POST["loginNU"], PDO::PARAM_STR);
            $login->execute();
            $login = $login->fetch(PDO::FETCH_ASSOC);

            if($login){
                echo "<script>alert('Ce login est déjà utilisé')</script>";
            }
            else{
                //On vérifie si l'adresse mail est déjà utilisée
                $email = $cnx->prepare("SELECT idEtudiant
                FROM etudiants
                WHERE email = :emailNU");
                $email->bindValue(":emailNU", $_POST["emailNU"], PDO::PARAM_STR);
                $email->execute();
                $email = $email->fetch(PDO::FETCH_ASSOC);

                if($email){
                    echo "<script>alert('Cette adresse mail est déjà utilisée')</script>";
                }
                else{
                    //On vérifie si les mdps sont identiques
                    if($_POST["passwordNU"] != $_POST["passwordNUC"]){
                        echo "<script>alert('La confirmation du mot de passe est différente')</script>";
                    }
                    else{
                        $inscription = true;
                    }
                }
            }
        }

        //Si les informations sont validées l'utilisateur est inscrit
        if($inscription){
            //On génère un id unique pour l'étudiant
            $verifierId = $cnx->prepare("SELECT idEtudiant
            FROM etudiants
            WHERE idEtudiant = :id");

            $idValide = false;
            while(!$idValide){
                $id = rand(1 , 10000);
                $verifierId->bindValue(":id", $id, PDO::PARAM_INT);
                $verifierId->execute();
                $verifierId = $verifierId->fetch(PDO::FETCH_ASSOC);

                //L'id est libre
                if(!$verifierId){
                    $idValide = true;
                }
            }
            
            //On crée l'étudiant dans la base
            $creerEtudiant = $cnx->prepare("INSERT INTO etudiants (idEtudiant, login, motDePasse, nom, prenom, email)
            VALUES (:id, :login, :mdp, :nom, :prenom, :email)");
            $creerEtudiant->bindValue(":id", $id, PDO::PARAM_INT);
            $creerEtudiant->bindValue(":login", $_POST["loginNU"], PDO::PARAM_STR);
            $creerEtudiant->bindValue(":mdp", $_POST["passwordNU"], PDO::PARAM_STR);
            $creerEtudiant->bindValue(":nom", $_POST["nomNU"], PDO::PARAM_STR);
            $creerEtudiant->bindValue(":prenom", $_POST["prenomNU"], PDO::PARAM_STR);
            $creerEtudiant->bindValue(":email", $_POST["emailNU"], PDO::PARAM_STR);
            $creerEtudiant->execute();
            header("Location: ../index.php");
            
        }


    ?>
    <div id="divInscription" class="flex flex-col justify-center items-center bg-green-200 border-green-400 border-2 rounded-2xl">
        <h2 class="font-bold text-green-700">Inscription</h2>
        <br><br>
        <form id="formInscription" class="flex flex-col justify-center items-center mb-6" action="./inscription.php" method="post">
            <input required type="text" name="loginNU" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="Login">
            <input required type="password" name="passwordNU" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="Mot de passe">
            <input required type="password" name="passwordNUC" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="Confirmation">
            <input required type="text" name="nomNU" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="Nom">
            <input required type="text" name="prenomNU" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="Prenom">
            <input required type="mail" name="emailNU" class="w-48 mt-2 border-2 rounded-md text-center placeholder-green-700 bg-green-100 border-green-400" placeholder="eMail">
            <input type="submit" name="btnInscription" value="Inscription" class="w-48 mt-2 border-2 rounded-md text-center text-green-700 bg-green-300 border-green-400">
        </form>
    </div>
</body>
</html>