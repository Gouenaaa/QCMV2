<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/resultat.css">
    <title>QR</title>
</head>
<body>
    <div id="divResultat">
        <?php
            session_start();
            include "cnx.php";

            //Au cas où l'utilisateur recharge la page
            if(!isset($_SESSION["resultats"])){
                header("Location: ./accueil.php");
            }
            else{

                //Récupération des réponses à la question précédente dans la liste
                //Les réponses étant dans la page précédente
                if(isset($_POST["reponse"])){
                    $_SESSION["resultats"][strval($_SESSION["idQuestionOrdre"])] = $_POST["reponse"];
                }
                else{
                    $_SESSION["resultats"][strval($_SESSION["idQuestionOrdre"])] = "Non répondu";
                }

                //On instancie un variable qui stock la note
                $note = 0;
        ?>
        <h2>Résultats</h2>
        <?php
                for($i = 1; $i <= $_SESSION["nbQuestions"]; $i++){
                    //On affecte les réponse à la question actuelle dans la liste / On vide la liste des réponses précédente
                    $listeReponses = array();
                    //On remplit la liste de réponse à cette question
                    if(!is_array($_SESSION["resultats"][strval($i)])){
                        array_push($listeReponses, $_SESSION["resultats"][strval($i)]);
                    }
                    else{
                        $listeReponses = $_SESSION["resultats"][strval($i)];
                    }

                    //Requete pour avoir les info de la $ième question
                    $question = $cnx->prepare("SELECT question.idQuestion, question.libelleQuestion, question.type, question.nbReponse, question.nbBonneReponse
                    FROM question
                    WHERE question.idQuestion IN(
                        SELECT questionquestionnaire.idQuestion
                        FROM questionquestionnaire
                        WHERE questionquestionnaire.idQuestionnaire = :idQuestionnaire
                        AND questionquestionnaire.odreQuestion = :ordreQuestion)");
                    $question->bindValue(":idQuestionnaire", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
                    $question->bindValue(":ordreQuestion", $i, PDO::PARAM_INT);
                    $question->execute();
                    $question = $question->fetch(PDO::FETCH_ASSOC);

                    //Requete pour avoir les réponses à cette question
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
                    $reponses->bindValue(":idQuestionActuelle", $question["idQuestion"], PDO::PARAM_INT);
                    $reponses->bindValue(":idQuestionnaire", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
                    $reponses->execute();
                    $reponses = $reponses->fetchAll(PDO::FETCH_ASSOC);

                    //Requete pour avoir les bonnes réponses à cette question
                    $bonnesReponses = $cnx->prepare("SELECT reponse.valeur
                    FROM reponse
                    JOIN questionreponse ON reponse.idReponse = questionreponse.idReponse
                    AND questionreponse.bonne = 1
                    AND questionreponse.idQuestion = :idQuestion");
                    $bonnesReponses->bindValue(":idQuestion", $question["idQuestion"], PDO::PARAM_INT);
                    $bonnesReponses->execute();

                    //On vérifie la validité des réponses
                    $questionValidee = false;
                    //On instancie la liste de bonnes réponses / On la vide des réponses précédentes
                    $listeBonnesReponses = array();
                    //On remplit la liste de bonne réponses à cette question
                    foreach($bonnesReponses->fetchAll(PDO::FETCH_ASSOC) as $br){
                        array_push($listeBonnesReponses, $br["valeur"]);
                    }
                    //Si le nombre de réponses est égal au nombre de bonnes réponses
                    if(sizeof($listeReponses) == $question["nbBonneReponse"]){
                        //On compare les réponses et les bonnes réponses
                        if($listeReponses == $listeBonnesReponses){
                            $questionValidee = true;
                        }
                        //Si une des réponses est fausse la question est ratée
                        else{
                            $questionValidee = false;
                        }
                    }

                    //Si la / les réponses sont bonnes la question rapporte un point
                    if($questionValidee){
                        $note++;
                    }

                    //Affichage de la question
                    //On récupère le nombre de bonnes réponses pour définir le type d'input
                    if($question["type"] == 1){
                        $type = "radio";
                        $array = "";
                    }
                    else{
                        $type = "checkbox";
                    }
                    echo "<div class='divQuestion'>";
                        echo "<div class='divTexte'>";
                            echo "<h3>Question ".$i.": ".$question["libelleQuestion"]."</h3>";
                            //On affiche les réponses
                            for($j = 0; $j < $question["nbReponse"]; $j++){
                                //On regarde si c'est la ou une des bonnes réponses
                                $checked = "";
                                $classReponse = "";
                                $labonne = "";
                                //Si cette réponse est une bonne réponse
                                if(in_array($reponses[$j]["valeur"], $listeBonnesReponses)){
                                    $classReponse = "vert";
                                    $checked = "checked=''";
                                    $labonne = "oui";
                                }
                                else{
                                    //Sinon Si elle a été choisie par l'utilisateur
                                    if(in_array($reponses[$j]["valeur"], $listeReponses)){
                                        $classReponse = "rouge";
                                        $checked = "checked=''";
                                    }
                                }
                                echo "<div class='divReponse'>";
                                echo "<input disabled ".$checked." type='".$type."' value='".$reponses[$j]["valeur"]."' name='reponseQuestion".$question["idQuestion"].$labonne."'>";
                                echo "<span class='".$classReponse."'>".$reponses[$j]["valeur"]."</span>";
                                echo "</div>";
                            }
                        echo "</div>";
                        echo "<div class='divImg'>";
                            if($questionValidee){
                                echo "<img src='../MEDIAS/cbon.png'>";
                            }
                            else{
                                echo "<img src='../MEDIAS/cpasbon.png'>";
                            }
                        echo "</div>";
                    echo "</div>";
                }

                //On affiche une barre de progression en fonction de la note
                $pourcentage = intval(($note / $_SESSION["nbQuestions"]) * 100);
                echo "<h3>Voici ta note: ".$note."/".$_SESSION["nbQuestions"]."</h3>";
                echo "<div id='barreProgression'>";
                    for($j = 0; $j < $pourcentage; $j++){
                        echo "<div class='vertP'>";
                        echo "</div>";
                    }
                echo "</div>";

                //On entre la note dans la base de donnée
                //On récupère la date
                $aujourdhui = getdate();
                $date = "";
                //strlen ne marche pas
                $date = $aujourdhui["year"];
                if(sizeof(str_split($aujourdhui["mon"])) == 1){
                    $date = $date."-0".$aujourdhui["mon"];
                }
                else{
                    $date = $date."-".$aujourdhui["mon"];
                }
                if(sizeof(str_split($aujourdhui["mday"])) == 1){
                    $date = $date."-0".$aujourdhui["mday"];
                }
                else{
                    $date = $date."-".$aujourdhui["mday"];
                }
                //On insère le passage dans la base;
                $insertion = $cnx->prepare("INSERT INTO qcmfait (idEtudiant, idQuestionnaire, dateFait, points)
                VALUES (:idEtudiant , :idQuestionnaire , :dateFait , :points)");
                $insertion->bindValue(":idEtudiant", $_SESSION["idU"], PDO::PARAM_INT);
                $insertion->bindValue(":idQuestionnaire", $_SESSION["idQuestionnaire"], PDO::PARAM_INT);
                $insertion->bindValue(":dateFait", $date, PDO::PARAM_STR);
                $insertion->bindValue(":points", $note, PDO::PARAM_INT);
                $insertion->execute();

                //On affiche un bouton pour retourner à l'accueil
                echo "<form id='frmFin' action='./accueil.php'>";
                echo "<input id='btnSubmit' type='submit' value='Fin du questionnaire'>";
                echo "</form>";
                //On vide les variables de session dont on a plus besoin
                unset($_SESSION["resultats"]);
                unset($_SESSION["idQuestionnaire"]);
        }
        ?>
    </div>
</body>
</html>