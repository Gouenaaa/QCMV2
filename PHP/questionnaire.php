<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/questionnaire.css">
    <title>QR</title>
</head>
<body>
    <?php
        session_start();
        include "./cnx.php";
        $page = "questionnaire";
        $finQ = "Valider";
        
        //Au 1er chargement de la page on compte le nombre de question
        if(isset($_GET["idQuestionnaire"])){
            //On sauvegarde l'id du questionnaire en cours
            $_SESSION["idQuestionnaire"] = $_GET["idQuestionnaire"];
            $nbQuestions = $cnx->prepare("SELECT COUNT(idQuestion)
             FROM question
             WHERE idQuestion IN(
                 SELECT idQuestion
                 FROM questionquestionnaire
                 WHERE idQuestionnaire = :idQ)");
            $nbQuestions->bindValue(":idQ", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
            $nbQuestions->execute();
            $nbQuestions = $nbQuestions->fetch(PDO::FETCH_NUM);
            $_SESSION["nbQuestions"] = $nbQuestions[0];
            $_SESSION["idQuestionOrdre"] = 1;
            $_SESSION["resultats"] = array();
        }
        //Aux chargements suivant
        else{
            //Récupération des réponses à la question précédente dans la liste
            if(isset($_POST["reponse"])){
                $_SESSION["resultats"][strval($_SESSION["idQuestionOrdre"])] = $_POST["reponse"];
            }
            else{
                $_SESSION["resultats"][strval($_SESSION["idQuestionOrdre"])] = "Non répondu";
            }
            
            //On incrémente la variable qui indique la question actuelle
            $_SESSION["idQuestionOrdre"] = $_SESSION["idQuestionOrdre"]+1;
            //Si c'est la dernière question on change les paramètres du formulaire et du bouton submit
            if($_SESSION["idQuestionOrdre"] == $_SESSION["nbQuestions"]){
                $page = "resultat";
                $finQ = "Terminer";
            }
        }

        //On récupère les infos de la question actuelle
        $infosQuestionActuelle = $cnx->prepare("SELECT question.idQuestion, question.libelleQuestion, question.type, question.nbReponse, question.cheminImgQuestion
        FROM question
        WHERE question.idQuestion IN(
            SELECT questionquestionnaire.idQuestion
            FROM questionquestionnaire
            WHERE questionquestionnaire.idQuestionnaire = :idQuestionnaire
            AND questionquestionnaire.odreQuestion = :ordreQuestion)");
        $infosQuestionActuelle->bindValue(":idQuestionnaire", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
        $infosQuestionActuelle->bindValue(":ordreQuestion", $_SESSION["idQuestionOrdre"], PDO::PARAM_INT);
        $infosQuestionActuelle->execute();
        $infosQuestionActuelle = $infosQuestionActuelle->fetch(PDO::FETCH_ASSOC);
        $_SESSION["idQuestionActuelle"] = $infosQuestionActuelle["idQuestion"];

        //On récupère les réponses de la question actuelle
        $reponses = $cnx->prepare("SELECT idReponse, valeur
        FROM reponse
        WHERE idReponse IN(
            SELECT idReponse
            FROM questionreponse
            WHERE idQuestion IN(
                SELECT idQuestion
                FROM question
                WHERE idQuestion = :idQuestionActuelle AND idQuestion IN(
                    SELECT idQuestion
                    FROM questionquestionnaire
                    WHERE idQuestionnaire = :idQuestionnaire)))");
        $reponses->bindValue(":idQuestionActuelle", $_SESSION["idQuestionActuelle"], PDO::PARAM_INT);
        $reponses->bindValue(":idQuestionnaire", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
        $reponses->execute();
        $reponses = $reponses->fetchAll(PDO::FETCH_ASSOC);

        //On affiche la question
        //On définie le type d'input selon le nombre de réponses correctes
        if($infosQuestionActuelle["type"] == 1){
            $type = "radio";
            $array = "";
        }
        else{
            $type = "checkbox";
            //Si plusieurs réponses sont attendues alors le paramètre dans l'URL doit être une liste
            $array = "[]";
        }
        echo "<div id='divQuestion'>";
            echo "<div id='divTexte'>";
            echo "<h2>Question ".$_SESSION["idQuestionOrdre"].": ".$infosQuestionActuelle["libelleQuestion"]."</h2>";
                echo "<form id='formQuestionnaire' action='./".$page.".php' method='post'>";
                //On affiche les réponses
                for($i = 0; $i < ($infosQuestionActuelle["nbReponse"]); $i++){
                    echo "<div class='divReponse'>";
                    echo "<input id='".$reponses[$i]["idReponse"]."' type='".$type."' value='".$reponses[$i]["valeur"]."' name='reponse".$array."'>";
                    echo "<label for='".$reponses[$i]["idReponse"]."'>".$reponses[$i]["valeur"]."</label>";
                    echo "</div>";
                }
                echo "<input id=btnSubmit type='submit' value='".$finQ."'>";
            echo "</form>";
            echo "</div>";
            echo "<div id='divImgQuestion'>";
                echo "<img id='imgReponse' src='".$infosQuestionActuelle["cheminImgQuestion"]."'";
            echo "</div>";
        echo "</div>";
    ?>
</body>
</html>