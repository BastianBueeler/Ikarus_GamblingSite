var setIkarusCoinsBtn = document.getElementById("setIkarusCoins");
var takeCardBtn = document.getElementById("takeCard");
var takeNoCardBtn = document.getElementById("takeNoCards");
var splitBtn = document.getElementById("split");
var doubleDownBtn = document.getElementById("doubleDown");

var myCardPlace = document.getElementById("myCards");
var dealerCardPlace = document.getElementById("dealerCards");

var myFortune = document.getElementById("fortune");
var myBet = document.getElementById("bet");

var promiseDealerTakeCard;

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
                        if(data[0].won == "dealer"){
                            alert("dealer hat gewonnen")
                        }else if(data[0].won == "my"){
                            alert("Sie haben gewonnen");
                        }
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
    
    if(myBet.innerHTML != ''){

        var request = new XMLHttpRequest();
        var url = 'ajaxCallHandler.php';
        request.open('POST', url, true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        var params = "function=takeCard&person=player";

        request.onload = function() {
            if(request.readyState == 4 && request.status == 200) {
                var data = JSON.parse(request.responseText);
                console.log(data);
                setCard("person", data[0].card);
                if(data[0].outcome == "over"){
                    setTimeout(function(){
                        alert("Sie haben verloren, über 21")
                    }, 2000);
                }else if(data[0].outcome == "won"){
                    setTimeout(function(){
                        alert("Sie habe gewonnen, 21");
                    }, 2000);
                }else{
                    playDealer();
                }
            }
        }

        request.send(params);
    }
    
});

takeNoCardBtn.addEventListener("click", function(){
    takeCardBtn.disabled = "disabled";
    takesDealerCard();
});

function takesDealerCard(){
    playDealer();
    
    setTimeout(function(){
        promiseDealerTakeCard
            .then(function (fulfilled){
                takesDealerCard();
            })
            .catch(function (error){

            });
    }, 2000);
}


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

function playDealer(){
    var takeCardAgain;
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = "function=takeCard&person=dealer";

    request.onload = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = JSON.parse(request.responseText);
            if(data[0].outcome == "cant"){
                takeCardAgain = false;
            }else{
                setCard("dealer", data[0].card);
                if(data[0].outcome == "over"){
                    setTimeout(function(){
                        alert("deler ist über 21");
                    }, 2000);
                    takeCardAgain = false;
                }else if(data[0].outcome == "won"){
                    setTimeout(function(){
                        alert("dealer hat gewonnen");
                    }, 2000);
                    takeCardAgain = false;
                }else{
                    takeCardAgain = true;
                }
            }
        }
    }

    request.send(params);

    setTimeout(function(){
        promiseDealerTakeCard = new Promise(
            function (resolve, reject) {
                if(takeCardAgain){
                    resolve("again");
                }else{
                    reject(new Error("cant"));
                }
            }
        );
    }, 2000);
}