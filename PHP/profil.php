<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/profil.css">
    <title>QR</title>
</head>
<body>
    <div id="head">
        <div id="divLogo">
            <a href="./accueil.php"><img id="imgLogo" src="../MEDIAS/logoContour.png"></a>
        </div>
        <div id="divDeconnexion">
            <a href="../index.php">Déconnexion</a>
        </div>
    </div>
    <?php
        session_start();
        include "./cnx.php";

        //On différencie les élèves des profs
        $grade = $cnx->prepare("SELECT grade
        FROM etudiants
        WHERE idEtudiant = :idU");
        $grade->bindValue(":idU", $_SESSION["idU"], PDO::PARAM_INT);
        $grade->execute();
        $grade = $grade->fetch(PDO::FETCH_ASSOC);

        if($grade["grade"] == 0){
            echo "<h2>Questionnaires terminés:</h2>";
            //On récupère tous les questionnaires finis par l'utilisateur
            $qcmFinis = $cnx->prepare("SELECT idQuestionnaire, libelleQuestionnaire, cheminImageQuestionnaire
            FROM questionnaire
            WHERE idQuestionnaire IN (
                SELECT idQuestionnaire
                FROM qcmfait
                WHERE idEtudiant = :idU)");
            $qcmFinis->bindValue(":idU", $_SESSION["idU"], PDO::PARAM_INT);
            $qcmFinis->execute();

            //On affiche les questionnaires un par un
            $nbQ = 1;
            foreach($qcmFinis->fetchAll(PDO::FETCH_ASSOC) as $ligne){
                if($nbQ == 1){
                    echo "<div class='divQuestionnaires'>";
                }
                echo "<div class='divQuestionnaire'>";
                echo "<div class='divImgQuestionnaire'>";
                echo "<a href='./questionnaire.php?idQuestionnaire=".$ligne["idQuestionnaire"]."'>";
                echo "<img class='imgQuestionnaire' src='".$ligne["cheminImageQuestionnaire"]."'>";
                echo "</a>";
                echo "</div>";
                echo $ligne["libelleQuestionnaire"];
                
                //On récupère le dernier passage
                $qcmFait = $cnx->prepare("SELECT dateFait, points
                FROM qcmfait
                WHERE dateFait = (SELECT MAX(dateFait) 
                    FROM qcmfait 
                    WHERE idEtudiant = :idU 
                    AND idQuestionnaire = :idQ)
                AND idFait = (SELECT MAX(idFait)
                    FROM qcmfait
                    WHERE idEtudiant = :idU 
                    AND idQuestionnaire = :idQ)");
                $qcmFait->bindValue(":idU", $_SESSION["idU"], PDO::PARAM_INT);
                $qcmFait->bindValue(":idQ", $ligne["idQuestionnaire"], PDO::PARAM_INT);
                $qcmFait->execute();
                $qcmFait = $qcmFait->fetch(PDO::FETCH_ASSOC);

                //On compte le nombre de question du questionnaire
                $nbQuestions = $cnx->prepare("SELECT COUNT(idQuestion)
                FROM questionquestionnaire
                WHERE idQuestionnaire = :idQ");
                $nbQuestions->bindValue(":idQ", $ligne["idQuestionnaire"], PDO::PARAM_INT);
                $nbQuestions->execute();
                $nbQuestions = $nbQuestions->fetch(PDO::FETCH_NUM);
                //On affiche les infos
                echo "<span>Dernier passage: ".$qcmFait["dateFait"]."</span>";
                echo "<span>Note: ".$qcmFait["points"]."/".$nbQuestions[0]."</span>";
                echo "<a href='./historique.php?idQ=".$ligne["idQuestionnaire"]."'>Historique</a>";
                echo "</div>";

                if($nbQ == 4){
                    echo "</div>";
                    $nbQ = 1;
                }
                else{
                    $nbQ = $nbQ + 1;
                }
            }
            if($nbQ != 4){
                echo "</div>";
            }
        }
        //Si c'est un prof
        else{

        }

        
    ?>
</body>
</html>