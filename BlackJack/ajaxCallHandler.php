<?php

if(isset($_POST['function'])){

    include("blackJackLogik.php");

    if($_POST['function'] === 'setIkarusCoins'){

        $logic = new BlackJackGame;

        session_start();
        session_regenerate_id(true);
            
        $bankAmount = $_SESSION['bankAmount'];
        $username = $_SESSION['username'];
        $amount = $_POST['value'];

        //erlaubnis von einsatz abfragen und im db schreiben
        $betPremission = $logic->setAmount($amount, $bankAmount, $username); 

        if($betPremission['premission']){

            $_SESSION['inputIkarusCoins'] = $amount;

            $cards = [];
            $cardsDealer = [];
            $cardsPlayer = [];
            
            //methode aufrufen für karten ziehen
            $currentStatusOfCards = $logic->getCard($cards, $cardsDealer);
            array_push($cards, $currentStatusOfCards['card']);
            array_push($cardsDealer, $currentStatusOfCards['card']);

            $currentStatusOfCards = $logic->getCard($cards, $cardsDealer);
            array_push($cards, $currentStatusOfCards['card']);
            array_push($cardsDealer, $currentStatusOfCards['card']);

            $dealerAmount = $currentStatusOfCards['cardsWorth'];

            $currentStatusOfCards = $logic->getCard($cards, $cardsPlayer);
            array_push($cards, $currentStatusOfCards['card']);
            array_push($cardsPlayer, $currentStatusOfCards['card']);

            $currentStatusOfCards = $logic->getCard($cards, $cardsPlayer);
            array_push($cards, $currentStatusOfCards['card']);
            array_push($cardsPlayer, $currentStatusOfCards['card']);

            $playerAmount = $currentStatusOfCards['cardsWorth'];
            
            //alle gezogenen karten speichern
            $_SESSION['takenCards'] = $cards;

            //gezogenen karten von dealer und player getrennt speichern
            $_SESSION['takenCardsOfDealer'] = $cardsDealer;
            $_SESSION['takenCardsOfPlayer'] = $cardsPlayer;

            //karten wert von player und dealer speichern
            $_SESSION['dealerCardsWorth'] = $dealerAmount;
            $_SESSION['playerCardsWorth'] = $playerAmount;

            //überprüfen ob jemand gewonnen hat
            if($playerAmount == 21 || $dealerAmount == 21){
                $winner = true;
            }else{
                $winner = false;
            }

            //array mit wichtigen daten erstellen
            $return = array( "premission" => $betPremission['premission'], "newBankAmount" => $betPremission['newBankAmount'], "dealerCards" => $cardsDealer, "playerCards" => $cardsPlayer, "winner" => $winner, "dealer" => $dealerAmount, "player" => $playerAmount);

            $logic->addOneToCountedBlackJackGames($username);
            $logic->addMoneyToMoneySpent($amount, $username);

        }else{
            //array mit wichtigen daten erstellen
            $return = array("premission" => $betPremission['premission']);

        }
        
        $arr = array($return);

        $json = json_encode($arr);
        //array mit wichtigen zurückgeben
        echo $json;

    }elseif($_POST['function'] === 'takeCard'){

        $logic = new BlackJackGame;

        session_start();
        session_regenerate_id(true);

        $cards = $_SESSION['takenCards'];

        //überprüfen wer zieht eine karte
        if($_POST['person'] == 'player'){
            $takenCardsOfPerson = $_SESSION['takenCardsOfPlayer'];
        }elseif($_POST['person'] == 'dealer'){
            $takenCardsOfPerson = $_SESSION['takenCardsOfDealer'];
        }

        $dealerAmount = $_SESSION['dealerCardsWorth'];

        //überprüfen ob dealer noch eine karte darf ziehen
        if($_POST['person'] == 'dealer' && $dealerAmount < 17 || $_POST['person'] == 'player'){

            //karte ziehen und neue werte speichern
            $currentStatusOfCards = $logic->getCard($cards, $takenCardsOfPerson);

            $card = $currentStatusOfCards['card'];
            $cardsWorth = $currentStatusOfCards['cardsWorth'];

            //überprüfen ob man gewonnen oder verlogen hat
            if($cardsWorth > 21){
                $loser = true;
                $winner = false;
            }elseif($cardsWorth == 21){
                $winner = true;
                $loser = false;
            }else{
                $winner = false;
                $loser = false;
            }

            array_push($cards, $card);
            $_SESSION['takenCards'] = $cards;

            //gezogenen karten von dealer oder player speichern
            //karten wert von player oder dealer speichern
            if($_POST['person'] == 'player'){
                $_SESSION['playerCardsWorth'] = $cardsWorth;
                
                $cardsPlayer = $_SESSION['takenCardsOfPlayer'];
                array_push($cardsPlayer, $card);
                $_SESSION['takenCardsOfPlayer'] = $cardsPlayer;

            }elseif($_POST['person'] == 'dealer'){
                $_SESSION['dealerCardsWorth'] = $cardsWorth;

                $cardsDealer = $_SESSION['takenCardsOfDealer'];
                array_push($cardsDealer, $card);
                $_SESSION['takenCardsOfDealer'] = $cardsDealer;
            }

             //array mit wichtigen daten erstellen
            $return = array("card" => $card, "canDealerTakeCard" => true, "winner" => $winner, "loser" => $loser);

        }else{

             //array mit wichtigen daten erstellen
            $return = array("canDealerTakeCard" => false);

        }
        
        $arr = array($return);

        $json = json_encode($arr);

        //array mit wichtigen zurückgeben
        echo $json;

    }elseif($_POST['function'] === 'getBankAmount'){

        //Ikaruscoins vermögen abfragen und speichern und zurück geben
        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame;

        $username = $_SESSION['username'];

        $bankAmount = $logic->getBankAmount($username);

        $_SESSION['bankAmount'] = $bankAmount;

        echo $bankAmount;

    }elseif($_POST['function'] === 'whoWon'){
        //überprüfen wer gewonnen hat

        session_start();
        session_regenerate_id(true);

        if($_SESSION['dealerCardsWorth'] > 21){
            echo 'playerWon';
        }elseif($_SESSION['playerCardsWorth'] > 21){
            echo 'dealerWon';
        }elseif($_SESSION['playerCardsWorth'] > $_SESSION['dealerCardsWorth']){
            echo 'playerWon';
        }elseif($_SESSION['playerCardsWorth'] < $_SESSION['dealerCardsWorth']){
            echo 'dealerWon';
        }else{
            echo 'draw';
        }

    }elseif($_POST['function'] === 'multiply'){

        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame;

        $bet = $_SESSION['inputIkarusCoins'];
        $bankAmount = $_SESSION['bankAmount'];
        $username = $_SESSION['username'];

        //schauen mit was einsatz multiplitziert wird (blackjack gibt höherer multiplikator)
        if($_SESSION['playerCardsWorth'] == 21){

            $bankStatement = $logic->multiplyBet(2.5, $bet, $bankAmount, $username);

        }else{

            $bankStatement = $logic->multiplyBet(2, $bet, $bankAmount, $username);

        }

        $_SESSION['bankAmount'] = $bankStatement['newBankAmount'];
        $_SESSION['moneyGetBack'] = $bankStatement['moneyGetBack'];
        
        //neues vermögen zurückgeben
        echo $bankStatement['newBankAmount'];

    }elseif($_POST['function'] == 'getBetBack'){
        //für unentschieden, den einsatz zurück erhalten

        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame();

        $bet = $_SESSION['inputIkarusCoins'];
        $bankAmount = $_SESSION['bankAmount'];
        $username = $_SESSION['username'];

        $bankStatement = $logic->getBetBack($bet, $bankAmount, $username);

        $_SESSION['bankAmount'] = $bankStatement['newBankAmount'];
        $_SESSION['moneyGetBack'] = $bankStatement['moneyGetBack'];

        //neues vermögen zurückgeben
        echo $bankStatement['newBankAmount'];

    }elseif($_POST['function'] == 'getEndOfGameInfo'){
        //informationen über das spielende sammeln und zurückgeben

        $logic = new BlackJackGame;

        session_start();
        session_regenerate_id(true);

        if($_POST['winner'] == 'dealer'){

            $return = [
                'dealerCardsWorth' => $_SESSION['dealerCardsWorth'],
                'playerCardsWorth' => $_SESSION['playerCardsWorth'],
                'bankAmount'       => $_SESSION['bankAmount'],
                'betInput'         => $_SESSION['inputIkarusCoins'],
                'moneyGetBack'     => 0,
            ];

        }elseif($_POST['winner'] == 'player'){

            $return = [
                'dealerCardsWorth' => $_SESSION['dealerCardsWorth'],
                'playerCardsWorth' => $_SESSION['playerCardsWorth'],
                'bankAmount'       => $_SESSION['bankAmount'],
                'betInput'         => $_SESSION['inputIkarusCoins'],
                'moneyGetBack'     => $_SESSION['moneyGetBack'],
            ];

            $logic->addOneToBlackJackWins($_SESSION['username']);
            $logic->addMoneyToMoneyWon($_SESSION['moneyGetBack'], $_SESSION['username']);

        }elseif($_POST['draw'] == 'draw'){

            $return =[
                'dealerCardsWorth' => $_SESSION['dealerCardsWorth'],
                'playerCardsWorth' => $_SESSION['playerCardsWorth'],
                'bankAmount'       => $_SESSION['bankAmount'],
                'betInput'         => $_SESSION['inputIkarusCoins'],
                'moneyGetBack'     => $_SESSION['moneyGetBack'],
            ];

            $logic->addMoneyToMoneyWon($_SESSION['moneyGetBack'], $_SESSION['username']);
        }

        $arr = array($return);

        $json = json_encode($arr);

        echo $json;
    }

}

?>