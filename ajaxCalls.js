var setIkarusCoinsBtn = document.getElementById("setIkarusCoins");
var takeCardBtn = document.getElementById("takeCard");
var splitBtn = document.getElementById("split");
var doubleDownBtn = document.getElementById("doubleDown");

var myCardPlace = document.getElementById("myCards");
var dealerCardPlace = document.getElementById("dealerCards");

var myFortune = document.getElementById("fortune");
var myBet = document.getElementById("bet");

var request = new XMLHttpRequest();
var url = 'ajaxCallHandler.php';
request.open('POST', url, true);
request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

request.onload = function(){
    if(request.readyState == 4 && request.status == 200) {
        var data = request.responseText;  
        myFortune.innerHTML = 'Sie besitzen ' + data + ' IkarusCoins';
    }
}

var params = 'function=getBankAmount';
request.send(params);


setIkarusCoinsBtn.addEventListener("click", function(){
    if(myBet.innerHTML == ''){
        var request = new XMLHttpRequest();
        var url = 'ajaxCallHandler.php';
        request.open('POST', url, true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        var inputIkarusCoins = document.getElementById("amountIkarusCoins").value;

        if(inputIkarusCoins != '' && Math.sign(inputIkarusCoins) != "-1"){
            var params = 'function=setIkarusCoins&value=' + inputIkarusCoins;   
        
            request.onload = function() {
                if(request.readyState == 4 && request.status == 200) {
                    var data = JSON.parse(request.responseText);
                    if(data[0].ergebnis == "false"){
                        alert("Sie haben nicht genügend Geld");
                    }else if(data[0].ergebnis == "true"){
                        myFortune.innerHTML = 'Sie besitzen ' + data[0].result + ' IkarusCoins';
                        myBet.innerHTML = 'Ihr Einsatz: ' + inputIkarusCoins;
                        setCard("dealer", data[0].dealerCards[0]);
                        setCard("dealer", data[0].dealerCards[1]);
                        setCard("person", data[0].myCards[0]);
                        setCard("person", data[0].myCards[1]);
                    }
                }
            }
            request.send(params);
        }else{
            alert("Geben sie eine gültige Zahl an");
        }
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

function setCard(person, card){

    var htmlString = '<img src="' + card + '.png" height="100%" width="160 class="cards>';

    if(person == "dealer"){
        dealerCardPlace.insertAdjacentHTML('beforeend', htmlString);
    }else if(person == "person") {
        myCardPlace.insertAdjacentHTML('beforeend', htmlString);
    }    
}