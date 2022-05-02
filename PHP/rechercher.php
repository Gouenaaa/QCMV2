<?php
    include "./cnx.php";

    $recherche = $cnx->prepare("SELECT idQuestion, libelleQuestion, type, nbReponse, nbBonneReponse
    FROM question
    WHERE libelleQuestion LIKE '%".$_POST["txt"]."%' 
    LIMIT 0, 5");
    $recherche->execute();

    foreach($recherche->fetchAll(PDO::FETCH_ASSOC) as $ligne){
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