$(document).ready(function(){  
    //Haku sivun rajauksen/kategorioden näyttäminen pienellä näytöllä
    $('#nayta').on("click", function() {
            if ( $("#nakyy").css('display') == 'none' ){
            $( "#nakyy" ).css( "display", "block" );
            $( "#nayta" ).css("border-radius", "60px 60px 0px 0px");
         }
         else{
             $( "#nakyy" ).css( "display", "none" );
             $( "#nayta" ).css("border-radius", "30px");
         }
    });
    
    //Valikko
    $('.nayta').on("click", function() {
            if ( $(".nakyy").css('display') == 'none' ){
            $( ".nakyy" ).css( "display", "flex" );
            $( ".nakyy" ).css( "flex-direction", "column" );
            $( ".add" ).css( "width", "200px" );
            $( ".nayta" ).css("width", "100%");
         }
         else{
             $( ".nakyy" ).css( "display", "none" );
             $( ".nayta" ).css("border-radius", "30px");
         }
    });
    //Roskiksen avaaminen
    $( ".roskis" ).hover(
  function() {
    $( this ).attr("src","kuvat/roskis_auki.png");
  }, function() {
    $( this ).attr("src","kuvat/roskis.png");
  }
);

    
    
    // Pienentää haku suurennuslasia hieman nappia klikatessa.
    $('body').on('click', '#hae_nappi', function (){
        $( '#hakukuva' ).css( "transform", "scale(0.90)" );
    });  

    //Kieliopillista hifistelyä,hakutulos vs hakutulosta
    if ($(".ilmoitus").length == '1'){
        $("#hifistelya").text("Hakutulos sanalle: ");
    }
     
    
//Tätä ylemmät on Jessen, alemmat on Aatun

     // Uuden salasanan lähetys AJAXilla
    $("#vaihdaSalasanaForm").submit(function(event) {
        event.preventDefault(); // Ettei formia lähetetä
        $.ajax({
            type: "POST",
            url: "changepwd.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                //console.log(data);
                $(".dirRow").children().css("width", "50%");
                
                if ($(".statusMessageDiv").is(":visible")) {
                    $(".statusMessageDiv").hide(250).delay(20);
                }
                setTimeout(function() {
                if (data == "Salasana vaihdettu onnistuneesti.") {
                    $(".statusMessageDiv").css("background-color", "lightgreen");
                } else {
                    $(".statusMessageDiv").css("background-color", "yellow");
                }
                $(".statusMessageDiv").show(250);//.css("display", "block");
                $("#statusMessage").text(data);
                }, 500);
            }
        });
    });
    $("#updateAccForm").submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "updateacc.php",
            data: $(this).serialize(),
            success: function(data) {
                var parsedData = jQuery.parseJSON(data);
                $(".dirRow").children().css("width", "50%");
                if ($(".statusMessageDiv").is(":visible")) {
                    $(".statusMessageDiv").hide(250).delay(20);
                }     
                setTimeout(function() {
                $(".statusMessageDiv").css("background-color", "lightgreen");
                $(".statusMessageDiv").show(250);
                $("#statusMessage2").text(parsedData);
                }, 500);
                
            }
        });
    });
    
    // Hakee suosikkien määrän piilotetusta sLkmHidden-elementistä
    var suosikitLkmGlobal = +$(".sLkmHidden").text();

    // Jos suosikkeja on enemmän kuin 0, niin suosikkien määrä näkyy
    if (suosikitLkmGlobal > 0) {
        $(".suosikitLkm").text(suosikitLkmGlobal).css("display", "inline-block");
    }
        
    var uvLkm = +$(".uudetviestitLkm").text();
    
    if (uvLkm !== "") {         $(".uudetviestitLkm").css("display","inline-block");
    } 
    if (uvLkm == "") {
    $(".uudetviestitLkm").css("display","none");    
    }
    //console.log("suosikitLkmGlobal:" + suosikitLkmGlobal);

 var timeOut; // Tämä on vähän hassussa paikassa

    // Suosikin lisääminen AJAXilla
    $(".addSuosikkiForm").submit(function(event) {
        event.preventDefault();
        
        var sydanImg = $(this).children(".sydan").attr("src");
        //console.log("sydän img:" + sydanImg);
        
    if (sydanImg == "kuvat/pois_sydan.png") {
            
        $(this).children(".sydan").attr("src", "kuvat/sydan.png");
            
        $.ajax({
            type: "POST",
            url: "delsuosikki.php",
            data: $(this).serialize(),
            success: function(data) {
                //console.log(data);
                var parsedData = jQuery.parseJSON(data);
                //console.log("parsedData:" + parsedData);
                var suosikitLkm = parsedData.length;
                //console.log("suosikitLkm:" + suosikitLkm);
               if (suosikitLkm == 0) {                                  
                   $(".suosikitLkm").addClass("uusiSuosikki");
                    setTimeout(function() {
                        $(".suosikitLkm").css("display", "none");
                    }, 500);
                } 
                
                $(".suosikitLkm").text(suosikitLkm).css("display", "inline-block");
                
                if (suosikitLkm != suosikitLkmGlobal) {
                    //console.log("temp console log");
                    $(".suosikitLkm").addClass("uusiSuosikki");
                    setTimeout(function() {
                        $(".suosikitLkm").removeClass("uusiSuosikki");
                    }, 800);
                } else { //console.log("Ei uutta suosikkia lisätty.");
                }
                suosikitLkmGlobal = suosikitLkm;
                //console.log("poistaminen onnistui jee");
            }
        });
    }
    else {
        
        $(this).children(".sydan").attr("src", "kuvat/pois_sydan.png");
        var img;
        var nimi;
            
        if (window.location.href.indexOf("index.php") > 0 ||
        window.location.href.indexOf("hae.php") > 0) {
            img = $(this).closest(".tyyliton").children(".tuotekuva").attr("src");
            nimi = $(this).closest(".tyyliton").children(".tuotteennimi").text();
        }
        else if (window.location.href.indexOf("account.php") > 0) {         img = $(this).closest(".tyyliton").children(".tuotekuva").attr("src");
            nimi = $(this).closest(".tyyliton").children(".tuotteennimi").text();
        }
        else {
            img = $(this).closest(".tyyliton").children(".oikeaIlmoitus").children(".tuotekuva").attr("src");
            nimi = $(this).closest(".ilmoitus2").children(".ilmoitusOtsikko").children(".tuotteennimi").text();
        }
        
        //console.log("img:" + img);
            
        $(".popUpNimi").text(nimi + " lisätty suosikkeihin!");
        $(".popUpImg").attr("src", img);
            
        if ($(".popUp").is(":hidden")) {
            $(".popUp").show(200);
        }
        
        if ($(".popUp").is(":visible")) {

            clearTimeout(timeOut);

            timeOut = setTimeout(function(){
                $(".popUp").hide(200);
            }, 2000);
        }
        
        $.ajax({
            type: "POST",
            url: "addsuosikki.php",
            data: $(this).serialize(),
            success: function(data) {
                //console.log(data);
                var parsedData = jQuery.parseJSON(data);
                //console.log("parsedData:" +parsedData);
                var suosikitLkm = parsedData.length;
                //console.log("suosikitLkm:" + suosikitLkm);
                $(".suosikitLkm").text(suosikitLkm).css("display", "inline-block");
                
                if (suosikitLkm != suosikitLkmGlobal) {
                    //console.log("temp console log");
                    $(".suosikitLkm").addClass("uusiSuosikki");
                    setTimeout(function() {
                        $(".suosikitLkm").removeClass("uusiSuosikki");
                    }, 800);
                } else { //console.log("Ei uutta suosikkia lisätty.");
                }
                suosikitLkmGlobal = suosikitLkm;
            }
        });
    }
    });
    
    $(".popUp").on("click", function() {
        $(".popUp").hide(50);
    });
    
        // Kommenttien peukutus AJAXilla
    $(".peukkuDiv").submit(function(event) {
        var peukkuLkm = +$(this).children(".peukkuLkm").text();
        var peukkuLkmSpan = $(this).children(".peukkuLkm");

        event.preventDefault();
        var uusiPeukkuLkm = $.ajax({
            type: "POST",
            url: "thumbsup.php",
            data: $(this).serialize(),
            success: function(data) {
                var parsedData = jQuery.parseJSON(data);
                if (parsedData == "notLoggedIn") {
                    window.location.replace("rekkirj/index.php");
                } 
                else {
                    var uusiPeukkuLkm = peukkuLkm + parsedData;
                    if (uusiPeukkuLkm < 1) uusiPeukkuLkm = "";
                    peukkuLkmSpan.text(uusiPeukkuLkm + " ");
                }
            }
        });
    });
    
    $("#btnOmatIlmoitukset").on("click", function() {
        $(".dirRow").children("h2").css("width", "100%");        
        if ($(".omatIlmoitukset").css("display", "none")) {
            $("#btnMuokkaaTietoja").css("background-color", "white");
            $("#btnMuokkaaTietoja").css("color", "black");
            $("#btnVaihdaSalasana").css("background-color", "white");
            $("#btnVaihdaSalasana").css("color", "black");
            
            $(".muokkaaTietoja").css("display", "none");
            $(".vaihdaSalasana").css("display", "none");
            $(".omatIlmoitukset").css("display", "flex");
            
            $(this).css("background-color", "darkblue");
            $(this).css("color", "white");
        }
    });
    
     $("#btnMuokkaaTietoja").on("click", function() {
        $(".dirRow").children("h2").css("width", "100%");         
         $(".statusMessageDiv").hide();
        if ($(".muokkaaTietoja").css("display", "none")) {
            $("#btnOmatIlmoitukset").css("background-color", "white");
            $("#btnOmatIlmoitukset").css("color", "black");
            $("#btnVaihdaSalasana").css("background-color", "white");
            $("#btnVaihdaSalasana").css("color", "black");
            
            $(".vaihdaSalasana").css("display", "none");
            $(".omatIlmoitukset").css("display", "none");
            $(".muokkaaTietoja").css("display", "flex");
            
            $(this).css("background-color", "darkblue");
            $(this).css("color", "white");
        } 
    });
    
    $("#btnVaihdaSalasana").on("click", function() {
        $(".dirRow").children("h2").css("width", "100%");            
        $(".statusMessageDiv").hide();
        if ($(".vaihdaSalasana").css("display", "none")) {
            $("#btnOmatIlmoitukset").css("background-color", "white");
            $("#btnOmatIlmoitukset").css("color", "black");
            $("#btnMuokkaaTietoja").css("background-color", "white");
            $("#btnMuokkaaTietoja").css("color", "black");
            
            $(".omatIlmoitukset").css("display", "none");
            $(".muokkaaTietoja").css("display", "none");
            $(".vaihdaSalasana").css("display", "flex");
            
            $(this).css("background-color", "darkblue");
            $(this).css("color", "white");
        }
    });
});

