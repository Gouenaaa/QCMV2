<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/creerQuestionnaire.css">
    <script src="../JS/JQuery.js"></script>
    <script src="../JS/fonctions.js"></script>
    <script>
        $(
            function(){
                // $(document).on("click", "input:button", function(){
                //     if($(this).attr("value") == "Supprimer"){
                //         var id = $(this).attr("id");
                //         $(this).val("Ajouter");
                //         $(this).parent().remove();
                //         $("#"+id+"").parent().fadeIn();
                //     }
                //     else{
                //         if($(this).attr("value") == "Ajouter"){
                //             $(this).val("Supprimer");
                //             $(this).parent().clone().appendTo("#divQuestionsQuestionnaire");
                //             $(this).parent().fadeOut();
                //         }
                        
                //     }
                // });
                $(document).on("click", "input:button", function(){
                    if($(this).attr("value") == "Supprimer"){
                        var id = $(this).attr("id");
                        $(this).parent().remove();
                        $("#"+id).val("Ajouter");
                        $("#"+id).show();  
                    }
                    else{
                        if($(this).attr("value") == "Ajouter"){
                            $(this).val("Supprimer");
                            $(this).parent().clone().appendTo("#divQuestionsQuestionnaire");
                            $(this).hide();
                        }
                        
                    }
                });

                
                $("#btnPageSui").click(pageSuivante);
                $("#btnPagePre").click(pagePrecedente);

                $("#btnRecherche").click(function(){
                    var txt = $("#inputRecherche").val();
                    rechercher(txt);
                });
            }
        );
    </script>
    <title>QR</title>
</head>
<body>
    <?php
    session_start();
    include "./cnx.php";

    if(isset($_GET["questions"])){
        //On récupère les multiples paramètres questions dans l'url
        $query  = explode('&', $_SERVER['QUERY_STRING']);
        $params = array();
        foreach( $query as $param )
        {
        list($name, $value) = explode('=', $param, 2);
        $params[urldecode($name)][] = urldecode($value);
        }

        

        //On obtient l'id du questionnaire
        $id = $cnx->prepare("SELECT MAX(idQuestionnaire)
                                FROM questionnaire");
        $id->execute();
        $id = $id->fetch(PDO::FETCH_NUM);
        $id = $id[0]+1;

        //On liste les id dans une chaine de caractère pour l'inserer dans la base
        $questions = "(";
        $i = 1;
        foreach($params["questions"] as $p){
            if($questions == "("){
                $questions = $questions.$id.", ".$p.", ".$i.")";
            }
            else{
                $questions = $questions.",(".$id.", ".$p.", ".$i.")";
            }
            $i++;
        }
        $questions = $questions.";";

        //On creer le questionnaire
        $creerQ = $cnx->prepare("INSERT INTO questionnaire (idQuestionnaire, libelleQuestionnaire, cheminImageQuestionnaire, idAuteur)
        VALUES (".$id.", '".$_GET["nom"]."', '".$_GET["lienimage"]."', ".$_SESSION["idU"].")");
        $creerQ->execute();

        //On fait correspondre les questions au questionnaire
        $questionsDansQuestionnaire = $cnx->prepare("INSERT INTO questionquestionnaire (idQuestionnaire, idQuestion, odreQuestion)
        VALUES ".$questions);
        $questionsDansQuestionnaire->execute();

        header("Location: ../PHP/accueil.php");
        
    }

    //RQT 5 premières réponses
    $pageAct = 1;
    $questions = $cnx->prepare("SELECT idQuestion, libelleQuestion, type, nbReponse, nbBonneReponse, cheminImgQuestion
                                FROM question
                                LIMIT 0, 5");
    $questions->execute();



    ?>

    <div id="head">
        <div id="divLogo">
            <a href="./accueil.php"><img id="imgLogo" src="../MEDIAS/logoContour.png"></a>
        </div>
        <div id="divProfil">
            <a href="./profil.php?idU=".<?php $_SESSION["idU"]?>><?php echo $_SESSION["loginU"]; ?></a>
            
        </div>
    </div>
    <div id="divCreerQuestionnaire">
        <form id="frmQuestionnaire" action="./creerQuestionnaire.php" method="get">
            <div id="divQuestionnaire">
                <input required class="inputQuestionnaire" type="text" name="nom" placeholder="Nom">
                <input required class="inputQuestionnaire" type="text" name="lienimage" placeholder="Lien image">
                <input class="inputQuestionnaire" type="submit" value="Créer le questionnaire">
            </div>
            <div id="divQuestionsQuestionnaire">
                <h3>Les questions: </h3>
            </div>
        </form>
        <div id="divRecherche">
            <input type="text" name="recherche" id="inputRecherche" placeholder="Rechercher">
            <input type="button" value="Rechercher" id="btnRecherche">
        </div>
        <div id="divQuestions">
            <?php
                foreach($questions->fetchAll(PDO::FETCH_ASSOC) as $ligne){
                    //RQT réponses
                    $reponses = $cnx->prepare("SELECT reponse.valeur, questionreponse.bonne
                                                FROM reponse, questionreponse
                                                WHERE questionreponse.idQuestion = :idQuestion
                                                AND questionreponse.idReponse = reponse.idReponse");
                    $reponses->bindValue(":idQuestion", $ligne["idQuestion"], PDO::PARAM_INT);
                    $reponses->execute();

                    echo "<div class='divQuestion'>";
                        echo "<h4>Q n°".$ligne["idQuestion"].": ".$ligne["libelleQuestion"]."</h4>";
                        foreach($reponses->fetchAll(PDO::FETCH_ASSOC) as $ligne2){
                            if($ligne2["bonne"] == 1){
                                $bonne = "class='bonne'";
                            }
                            else{
                                $bonne = "";
                            }
                            echo "<h5 ".$bonne.">".$ligne2["valeur"]."</h5>";
                        }
                        echo "<input id='".$ligne["idQuestion"]."' class='btn' type='button' value='Ajouter'>";
                        echo "<input type='hidden' name='questions' value='".$ligne["idQuestion"]."'>";
                        echo "<script>";
                            echo "$('#".$ligne["idQuestion"]."').click(ajouterQuestion);";
                        echo "</script>";
                    
                    echo "</div>";  
                }
            ?>
        </div>
        <div id="divBtnsPages">
            <input type="button" value="<-" id="btnPagePre">
            <input id="btnNb" type="button" value="<?php echo $pageAct; ?>">
            <input type="button" value="->" id="btnPageSui">
        </div>
    </div>
</body>
</html>