<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/historique.css">
    <title>QR</title>
</head>
<body>
    <?php
        session_start();
        include "./cnx.php";

        //On récupère le dernier passage pour la division principale
        $dernier = $cnx->prepare("SELECT dateFait, points, idFait
        FROM qcmfait
        WHERE dateFait = (SELECT MAX(dateFait) 
            FROM qcmfait 
            WHERE idEtudiant = :idU 
            AND idQuestionnaire = :idQ)
        AND idFait = (SELECT MAX(idFait)
            FROM qcmfait
            WHERE idEtudiant = :idU 
            AND idQuestionnaire = :idQ)");
        $dernier->bindValue(":idU", $_SESSION["idU"], PDO::PARAM_INT);
        $dernier->bindValue(":idQ", $_GET["idQ"], PDO::PARAM_INT);
        $dernier->execute();
        $dernier = $dernier->fetch(PDO::FETCH_ASSOC);

        //On récupère les infos du questionnaire
        $infoQ = $cnx->prepare("SELECT libelleQuestionnaire, cheminImageQuestionnaire
        FROM questionnaire
        WHERE idQuestionnaire = :idQ");
        $infoQ->bindValue("idQ", $_GET["idQ"], PDO::PARAM_INT);
        $infoQ->execute();
        $infoQ = $infoQ->fetch(PDO::FETCH_ASSOC);

        //On compte le nombre de question du questionnaire
        $nbQuestions = $cnx->prepare("SELECT COUNT(idQuestion)
        FROM questionquestionnaire
        WHERE idQuestionnaire = :idQ");
        $nbQuestions->bindValue(":idQ", $_GET["idQ"], PDO::PARAM_INT);
        $nbQuestions->execute();
        $nbQuestions = $nbQuestions->fetch(PDO::FETCH_NUM);
    ?>  
    <div id="head">
        <div id="divLogo">
            <a href="./accueil.php"><img id="imgLogo" src="../MEDIAS/logoContour.png"></a>
        </div>
        <div id="divProfil">
            <a href="./profil.php"><?php echo $_SESSION["loginU"]; ?></a>
        </div>
    </div>
    <div id="divQuestionnaire">
        <div id="divImgQuestionnaire">
            <img id="imgQuestionnaire" src="<?php echo $infoQ["cheminImageQuestionnaire"]; ?>">
        </div>
        <div id="divInfoQuestionnaire">
            <p>Questionnaire: <?php echo $infoQ["libelleQuestionnaire"]; ?></p>
            <p>Date du dernier passage: <?php echo $dernier["dateFait"]; ?></p>
            <p>Note: <?php echo $dernier["points"]; ?>/<?php echo $nbQuestions[0]; ?></p>
        </div>
    </div>
    <?php
        //On récupère tous les passages sauf le dernier
        $passages = $cnx->prepare("SELECT dateFait, points, idEtudiant
        FROM qcmfait
        WHERE idFait != :idF
        AND idEtudiant = :idU
        AND idQuestionnaire = :idQ
        ORDER BY idFait DESC");
        $passages->bindValue(":idF", $dernier["idFait"], PDO::PARAM_INT);
        $passages->bindValue(":idU", $_SESSION["idU"], PDO::PARAM_INT);
        $passages->bindValue(":idQ", $_GET["idQ"], PDO::PARAM_INT);
        $passages->execute();
        
        //On affiche une div pour chaque passage
        $nbDivLigne = 1;
        foreach($passages->fetchAll(PDO::FETCH_ASSOC) as $ligne){
            if($nbDivLigne == 1){
                echo "<div class='divPassages'>";
            }
            echo "<div class='divPassage'>";
            echo "<span>Date passage: ".$ligne["dateFait"]."</span><br>";
            echo "<span>Note: ".$ligne["points"]."/".$nbQuestions[0]."</span>";
            echo "</div>";


            if($nbDivLigne == 4){
                echo "</div>";
                $nbDivLigne = 1;
            }
            else{
                $nbDivLigne = $nbDivLigne + 1;
            }
        }
        if($nbDivLigne != 4){
            echo "</div>";
        }


    ?>
</body>
</html>