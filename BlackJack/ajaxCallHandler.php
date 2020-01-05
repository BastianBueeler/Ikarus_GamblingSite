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

        $betPremission = $logic->setAmount($amount, $bankAmount, $username); 

        if($betPremission['premission']){

            $_SESSION['inputIkarusCoins'] = $amount;

            $cards = [];
            $cardsDealer = [];
            $cardsPlayer = [];
            
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
            
            $_SESSION['takenCards'] = $cards;

            $_SESSION['takenCardsOfDealer'] = $cardsDealer;
            $_SESSION['takenCardsOfPlayer'] = $cardsPlayer;

            $_SESSION['dealerCardsWorth'] = $dealerAmount;
            $_SESSION['playerCardsWorth'] = $playerAmount;

            if($playerAmount == 21 || $dealerAmount == 21){
                $winner = true;
            }else{
                $winner = false;
            }

            $return = array( "premission" => $betPremission['premission'], "newBankAmount" => $betPremission['newBankAmount'], "dealerCards" => $cardsDealer, "playerCards" => $cardsPlayer, "winner" => $winner, "dealer" => $dealerAmount, "player" => $playerAmount);

        }else{

            $return = array("premission" => $betPremission['premission']);

        }
        
        $arr = array($return);

        $json = json_encode($arr);

        echo $json;

    }elseif($_POST['function'] === 'takeCard'){

        $logic = new BlackJackGame;

        session_start();
        session_regenerate_id(true);

        $cards = $_SESSION['takenCards'];
        if($_POST['person'] == 'player'){
            $takenCardsOfPerson = $_SESSION['takenCardsOfPlayer'];
        }elseif($_POST['person'] == 'dealer'){
            $takenCardsOfPerson = $_SESSION['takenCardsOfDealer'];
        }

        $dealerAmount = $_SESSION['dealerCardsWorth'];

        if($_POST['person'] == 'dealer' && $dealerAmount < 17 || $_POST['person'] == 'player'){

            $currentStatusOfCards = $logic->getCard($cards, $takenCardsOfPerson);

            $card = $currentStatusOfCards['card'];
            $cardsWorth = $currentStatusOfCards['cardsWorth'];

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

            $return = array("card" => $card, "canDealerTakeCard" => true, "winner" => $winner, "loser" => $loser);

        }else{

            $return = array("canDealerTakeCard" => false);

        }
        
        $arr = array($return);

        $json = json_encode($arr);

        echo $json;

    }elseif($_POST['function'] === 'getBankAmount'){

        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame;

        $username = $_SESSION['username'];

        $bankAmount = $logic->getBankAmount($username);

        $_SESSION['bankAmount'] = $bankAmount;

        echo $bankAmount;

    }elseif($_POST['function'] === 'whoWon'){
        
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

        if($_SESSION['playerCardsWorth'] == 21){

            $bankStatement = $logic->multiplyBet(2.5, $bet, $bankAmount, $username);

        }else{

            $bankStatement = $logic->multiplyBet(2, $bet, $bankAmount, $username);

        }

        $_SESSION['bankAmount'] = $bankStatement['newBankAmount'];
        $_SESSION['moneyGetBack'] = $bankStatement['moneyGetBack'];
        
        echo $bankStatement['newBankAmount'];

    }elseif($_POST['function'] == 'getBetBack'){

        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame();

        $bet = $_SESSION['inputIkarusCoins'];
        $bankAmount = $_SESSION['bankAmount'];
        $username = $_SESSION['username'];

        $bankStatement = $logic->getBetBack($bet, $bankAmount, $username);

        $_SESSION['bankAmount'] = $bankStatement['newBankAmount'];
        $_SESSION['moneyGetBack'] = $bankStatement['moneyGetBack'];

        echo $bankStatement['newBankAmount'];

    }elseif($_POST['function'] == 'getEndOfGameInfo'){
        
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

        }elseif($_POST['draw'] == 'draw'){

            $return =[
                'dealerCardsWorth' => $_SESSION['dealerCardsWorth'],
                'playerCardsWorth' => $_SESSION['playerCardsWorth'],
                'bankAmount'       => $_SESSION['bankAmount'],
                'betInput'         => $_SESSION['inputIkarusCoins'],
                'moneyGetBack'     => $_SESSION['moneyGetBack'],
            ];

        }

        $arr = array($return);

        $json = json_encode($arr);

        echo $json;
    }

}

?>