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
            
            $currentStatusOfCards = $logic->getCard($cards, 0);
            array_push($cards, $currentStatusOfCards['card']);

            $cardsWorthDealer = $currentStatusOfCards['cardsWorth'];

            $currentStatusOfCards = $logic->getCard($cards, $cardsWorthDealer);
            array_push($cards, $currentStatusOfCards['card']);

            $_SESSION['dealerCardsWorth'] = $currentStatusOfCards['cardsWorth'];

            $currentStatusOfCards = $logic->getCard($cards, 0);
            array_push($cards, $currentStatusOfCards['card']);

            $cardsWorthPlayer = $currentStatusOfCards['cardsWorth'];

            $currentStatusOfCards = $logic->getCard($cards, $cardsWorthPlayer);
            array_push($cards, $currentStatusOfCards['card']);

            $_SESSION['playerCardsWorth'] = $currentStatusOfCards['cardsWorth'];

            $dealerCards = [
                $cards[0],
                $cards[1],
            ];

            $playerCards = [
                $cards[2],
                $cards[3],
            ];
            
            $_SESSION['takenCards'] = $cards;
            
            $playerAmount = $_SESSION['playerCardsWorth'];
            $dealerAmount = $_SESSION['dealerCardsWorth'];

            if($playerAmount == 21 || $dealerAmount == 21){
                $winner = true;
            }else{
                $winner = false;
            }

            $return = array( "premission" => $betPremission['premission'], "newBankAmount" => $betPremission['newBankAmount'], "dealerCards" => $dealerCards, "playerCards" => $playerCards, "winner" => $winner);

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
            $amount = $_SESSION['playerCardsWorth'];
        }elseif($_POST['person'] == 'dealer'){
            $amount = $_SESSION['dealerCardsWorth'];
        }

        if($_POST['person'] == 'dealer' && $amount < 17 || $_POST['person'] == 'player'){

            $currentStatusOfCards = $logic->getCard($cards, $amount);

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
            }elseif($_POST['person'] == 'dealer'){
                $_SESSION['dealerCardsWorth'] = $cardsWorth;
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