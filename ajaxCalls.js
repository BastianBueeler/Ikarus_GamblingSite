var setIkarusCoinsBtn = document.getElementById("setIkarusCoins");
var takeCardBtn = document.getElementById("takeCard");
var splitBtn = document.getElementById("split");
var doubleDownBtn = document.getElementById("doubleDown");

var myCardPlace = document.getElementById("myCards");
var dealerCardPlace = document.getElementById("dealerCards");

setIkarusCoinsBtn.addEventListener("click", function(){

    var inputIkarusCoins = document.getElementById("amountIkarusCoins").value;

    if(inputIkarusCoins != ''){
        
        var request = new XMLHttpRequest();
        var url = 'ajaxCallHandler.php';
        var params = 'function=setIkarusCoins&value=' + inputIkarusCoins;   
        request.open('POST', url, true);


        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        request.onload = function() {
            if(request.readyState == 4 && request.status == 200) {
                /*var data = JSON.parse(request.responseText);
                if(data[0].ergebnis === "false"){
                    alert("Sie haben nicht genügend Geld");
                }*/
                console.log(request.responseText);
            }
        }

        request.send(params);

    }else{
        alert("Geben sie ihren Einsatz an");
    }
});

takeCardBtn.addEventListener("click", function(){

    request.onload = function() {
        if(request.readyState == 4 && request.status == 200) {
            alert(request.responseText);
        }
    }

    request.send(params);

});

splitBtn.addEventListener("click", function(){

    var params = 'function=split';

    request.onload = function() {
        if(request.readyState == 4 && request.status == 200) {
            alert(request.responseText);
        }
    }

    request.send(params);

});

doubleDownBtn.addEventListener("click", function(){

    var params = 'function= ';

    request.onload = function() {
        if(request.readyState == 4 && request.status == 200) {
            alert(request.responseText);
        }
    }

    request.send(params);

});

/*
var htmlString = '<img src="ecke2.png" alt="ecke2" height="100%" width="160" class="cards">'

mycardplace.insertAdjacentHTML('beforeend', htmlString);
*/