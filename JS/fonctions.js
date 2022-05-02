commence = false;
function AfficherLogo(){
    $("#divLogo").empty();
    $("#divLogo").append('<img id="imgLogo" src="./MEDIAS/logo.png" alt="">');
}

function AfficherNom(){
    if(!commence){
        $("#divLogo").empty();
        $("#divLogo").append('<img id="imgNom" src="./MEDIAS/nom.png" alt="">');    
    }
    }

function sleep(milliseconds){
    var start = new Date().getTime();
    for(var i = 0; i < 1e7; i++){
        if((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}

function Son(){
    commence = true;
    new Audio('../MEDIAS/opening.mp3').play();
    Tourne();
}


var degre = 0;
var larg = 250;
function Tourne(){
    if(degre == 0){
        sleep(2000);
    }
    degre += 10;
    larg -= 2;
    $('#imgLogo').css('transform','rotate('+degre+'deg)').css('width',larg);
    if(larg != 0){
        automateTourne = setTimeout('Tourne()',10);
    }
    if(larg == 0){
        divConnexion();
    }
}

function divConnexion(){
    $("body").empty();
    $("body").append("<div id='divConnexion' class='flex justify-center items-center flex-col bg-green-200 border-green-400 border-2 rounded-2xl'></div>");
    $("#divConnexion").append("<h2 class='font-bold text-green-700'>Connexion</h2>");
    $("#divConnexion").append("<form id='formConnexion' class='mt-4 content-evenly flex flex-col justify-center items-center' action='index.php' method='post'></form>");
    $("#formConnexion").append("<input required type='text' name='loginU' class='w-48 bg-green-100 placeholder-green-400 border-2 mt-2 rounded-md text-center border-green-400' placeholder='Login' >");
    $("#formConnexion").append("<input required type='password' name='passwordU' class='w-48 bg-green-100 placeholder-green-400 border-2 mt-2 rounded-md text-center border-green-400' placeholder='Mot de passe'>");
    $("#formConnexion").append("<input class='w-48 mt-2 border-2 rounded-md text-center text-green-700 bg-green-300 border-green-400' type='submit' name='btnConnexion' value='Connexion'>");
    $("#divConnexion").append("</form>");
    $("#divConnexion").append("<form id='formInscription' action='./PHP/inscription.php' method='get'>");
    $("#formInscription").append("<input class='w-48 mt-6 border-2 rounded-md text-center text-green-700 bg-green-300 border-green-400' type='submit' name='btnInscription' value='Inscription'>");
    $("#divConnexion").append("</form>");
    
}


var r = 2;
function ajouterReponse(){
    r += 1;
    $("#btnSupprimer").remove();  
    $("#divReponses").append("<div class='divReponse' id='divReponse"+r+"'>");
    $("#divReponse"+r+"").append("<input required type='text' name='reponse"+r+"' id='txtReponse"+r+"' placeholder='Réponse "+r+"'>");
    $("#divReponse"+r+"").append("<input type='checkbox' name='bonneReponse"+r+"' class='cbxReponse'>");
    $("#divReponse"+r+"").append("<label for='cbxReponse'>Bonne Réponse</label>");
    $("#divReponse"+r+"").append("<input type='button' value='Supprimer Reponse' id='btnSupprimer'>");
    $("#divReponses").append("</div>");
    $("#vr").remove();
    $("#frmQuestion").append("<input id='vr' type='hidden' name='vr' value='"+r+"'>");
}

function ajouterQuestion(){
    
}

var nieme = 0;
var pageAct = 1;
function page(){
    $("#btnNb").val(pageAct);
}


function pageSuivante(){
    nieme = nieme + 5;
    pageAct = pageAct + 1;
    $.ajax(
        {
            method:"post",
            url:"../PHP/pageSuivante.php",
            data:"nieme="+nieme+"&pageAct="+pageAct,
            success:function(donnees){
                if(donnees){
                    $("#divQuestions").empty();
                    $("#divQuestions").append(donnees);
                    page();
                }
                else{
                    nieme = nieme - 5;
                    pageAct = pageAct - 1;
                }
                
            },
            error:function(){
                alert("Erreur sur la RQT PAGESUIVANTE");
            },
        }
    );
}

function pagePrecedente(){
    nieme = nieme - 5;
    pageAct = pageAct - 1;
    $.ajax(
        {
            method:"post",
            url:"../PHP/pagePrecedente.php",
            data:"nieme="+nieme+"&pageAct="+pageAct,
            success:function(donnees){
                if(donnees){
                    $("#divQuestions").empty();
                    $("#divQuestions").append(donnees);
                    page();
                }
                else{
                    nieme = nieme + 5;
                    pageAct = pageAct + 1;
                }
                
            },
            error:function(){
                alert("Erreur sur la RQT PAGESUIVANTE");
            },
        }
    );
}

function rechercher(txt){
    $.ajax(
        {
            method:"post",
            url:"../PHP/rechercher.php",
            data:"txt="+txt,
            success:function(donnees){
                if(donnees){
                    $("#divQuestions").empty();
                    $("#divQuestions").append(donnees);
                }
                
            },
            error:function(){
                alert("Erreur sur la RQT RECHERCHER");
            },
        }
    );


}

