var setIkarusCoinsBtn = document.getElementById("setIkarusCoins");
var takeCardBtn = document.getElementById("takeCard");
var takeNoCardBtn = document.getElementById("takeNoCards");

var myCardPlace = document.getElementById("myCards");
var dealerCardPlace = document.getElementById("dealerCards");
var blackJackContent = document.getElementById("blackJackContent");

var myFortune = document.getElementById("fortune");
var myBet = document.getElementById("bet");

var promiseDealerTakeCard;
var promiseGetEndOfGameInfo;

takeCardBtn.disabled = true;
takeNoCardBtn.disabled = true;


var request = new XMLHttpRequest();
var url = 'ajaxCallHandler.php';
request.open('POST', url, true);
request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

request.onload = function(){
    if(request.readyState == 4 && request.status == 200) {
        var data = request.responseText;  
        setFortune(data);
    }
}

var params = 'function=getBankAmount';
request.send(params);

function setFortune(fortune){

    myFortune.innerHTML = 'Sie besitzen ' + fortune + ' IkarusCoins';
    
}

setIkarusCoinsBtn.addEventListener("click", function(){
    if(myBet.innerHTML == ''){
        var request = new XMLHttpRequest();
        var url = 'ajaxCallHandler.php';
        request.open('POST', url, true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        var inputIkarusCoins = document.getElementById("amountIkarusCoins").value;

        if(inputIkarusCoins != '' && Math.sign(inputIkarusCoins) != "-1"){
            if(inputIkarusCoins % 2 == 0){
                var params = 'function=setIkarusCoins&value=' + inputIkarusCoins;   
        
                request.onload = function() {
                    if(request.readyState == 4 && request.status == 200) {
                        var data = JSON.parse(request.responseText);
                        if(data[0].premission){

                            takeCardBtn.disabled = false;
                            takeNoCardBtn.disabled = false;
                            setIkarusCoinsBtn.disabled = true;$

                            setFortune(data[0].newBankAmount);
                            myBet.innerHTML = 'Ihr Einsatz: ' + inputIkarusCoins;

                            setCard("dealer", data[0].dealerCards[0]);
                            setCard("dealer", data[0].dealerCards[1]);
                            setCard("player", data[0].playerCards[0]);
                            setCard("player", data[0].playerCards[1]);
                            
                            if(data[0].winner){
                                whoWon();
                            }

                        }else{
                            alert("Sie haben nicht genügend Geld");
                        }
                    }
                }

                request.send(params);

            }else{
                alert("Sie müssen eine gerade Zahl angeben")
            }
        }else{
            alert("Geben sie eine gültige Zahl an");
        }
    }
});


takeCardBtn.addEventListener("click", function(){
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = "function=takeCard&person=player";

    request.onload = function() {
        if(request.readyState == 4 && request.status == 200) {
            var data = JSON.parse(request.responseText);
            setCard("player", data[0].card);
            if(data[0].loser){
                setTimeout(function(){
                    whoWon();
                }, 2000);
            }else if(data[0].winner){
                setTimeout(function(){
                    whoWon();
                }, 2000);
            }else{
                playDealer();
            }
        }
    }

    request.send(params);
    
});

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
            if(data[0].canDealerTakeCard){
                
                setCard("dealer", data[0].card)
                
                if(data[0].loser){
                    setTimeout(function(){
                        whoWon();
                    }, 2000);

                    takeCardAgain = false;

                }else if(data[0].winner){
                    setTimeout(function(){
                        whoWon();
                    }, 2000);

                    takeCardAgain = false;
                }else{
                    takeCardAgain = true;
                }

            }else{
                takeCardAgain = false;
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

takeNoCardBtn.addEventListener("click", function(){
    takeCardBtn.disabled = true;
    takeNoCardBtn.disabled = true;
    fillDealerCardsWorthUntil17();
});

function fillDealerCardsWorthUntil17(){
    playDealer();
    
    setTimeout(function(){
        promiseDealerTakeCard
            .then(function (fulfilled){
                fillDealerCardsWorthUntil17();
            })
            .catch(function (error){
                whoWon();
            });
    }, 2000);
}

function whoWon(){
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = "function=whoWon";

    request.onload = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = request.responseText;

            if(data == 'playerWon'){
                multiplyBet();
                displayWinner('player');
            }else if(data == 'dealerWon'){
                displayWinner('dealer')
            }else{
                draw()
                displayWinner('draw');
            }
        }
    }

    request.send(params);
}

function multiplyBet(){
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = "function=multiply";

    request.onload = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = request.responseText;
            setFortune(data);  
        }
    }

    request.send(params);   
}

function draw(){
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = "function=getBetBack";

    request.onload = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = request.responseText;
            setFortune(data);  
        }
    }

    request.send(params);
}


function setCard(person, card){

    var htmlString = '<img src="cardsImg/' + card + '.png" height="100%" width="160 class="cards>';

    if(person == "dealer"){
        dealerCardPlace.insertAdjacentHTML('beforeend', htmlString);
    }else if(person == "player") {
        myCardPlace.insertAdjacentHTML('beforeend', htmlString);
    }    
}

function displayWinner(winner){
    var winnerString;
    var endOfGameInfo;

    if(winner == 'player'){
        winnerString = 'Sie haben gewonnen';
    }else if(winner == 'dealer'){
        winnerString = 'Dealer hat gewonnen';
    }else{
        winnerString = 'draw';
    }

    getEndOfGameInfo(winner);

    setTimeout(function(){
        promiseGetEndOfGameInfo
            .then(function (fulfilled){
                endOfGameInfo = '<div style="width: 100%; margin-left: 5%">'
                endOfGameInfo += '<p>Dealer Kartenwert: ' + fulfilled.dealerCardsWorth + '</p>';
                endOfGameInfo += '<p>Ihren Kartenwert: ' + fulfilled.playerCardsWorth + '</p>';
                endOfGameInfo += '<p>Sie haben in dieser Runde ' + fulfilled.betInput + ' IkarusCoins eingesetzt</p>';
                endOfGameInfo += '<p>Sie haben in dieser Runde ' + fulfilled.moneyGetBack + ' IkarusCoins erhalten</p>';
                endOfGameInfo += '</div>';

                var htmlString = '<div style="height: 400px; width: 45%; z-index: 1; position: absolute;" class="border bg-white border-dark shadow rounded">';
                
                htmlString += '<p class="display-4" style="width:100%; text-align: center;">' + winnerString + '</p></br>';
                htmlString += endOfGameInfo;
                htmlString += '<a href="BalckJackFrontend.html" style="margin-bottom: 10px;"><button style="margin-bottom: 10px; margin-left: 5%; margin-right: 5%; width: 90%" class="btn btn-secondary mt-5">Nochmals</button></a></div>';
            
                blackJackContent.insertAdjacentHTML("beforeend", htmlString);
            })
            .catch(function (error){
                
                displayWinner(winner);

            });
    }, 2000);

}

function getEndOfGameInfo(winner){
    var request = new XMLHttpRequest();
    var url = 'ajaxCallHandler.php';
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    var params = 'function=getEndOfGameInfo&winner=' + winner;
    var data;

    request.onload = function(){
        if(request.readyState == 4 && request.status == 200){
            data = JSON.parse(request.responseText);
        }
    }

    request.send(params);

    setTimeout(function(){
        promiseGetEndOfGameInfo = new Promise(
            function (resolve, reject) {
                if(data){
                    var info = {
                        dealerCardsWorth: data[0].dealerCardsWorth,
                        playerCardsWorth: data[0].playerCardsWorth,
                        bankAmount: data[0].bankAmount,
                        betInput : data[0].betInput,
                        moneyGetBack: data[0].moneyGetBack,
                    };
                    resolve(info);                    
                }else{
                    reject(new Error("cant"));
                }
            }
        );
    }, 2000);

}