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

        $result = $logic->setAmount($amount, $bankAmount, $username); 

        if($result != ''){

            $_SESSION['inputIkarusCoins'] = $amount;

            $cards = [];
            
            $getCardReturn = $logic->getCard($cards, 0);
            array_push($cards, $getCardReturn[0]);

            $cardAmountDealer = $getCardReturn[1];

            $getCardReturn = $logic->getCard($cards, $cardAmountDealer);
            array_push($cards, $getCardReturn[0]);

            $_SESSION['dealerCardsAmount'] = $getCardReturn[1];

            $getCardReturn = $logic->getCard($cards, 0);
            array_push($cards, $getCardReturn[0]);

            $myCardAmount = $getCardReturn[1];

            $getCardReturn = $logic->getCard($cards, $myCardAmount);
            array_push($cards, $getCardReturn[0]);

            $_SESSION['myCardsAmount'] = $getCardReturn[1];

            $dealerCards = [
                $cards[0],
                $cards[1],
            ];

            $myCards = [
                $cards[2],
                $cards[3],
            ];
            
            $_SESSION['takenCards'] = $cards;
            
            $myAmount = $_SESSION['myCardsAmount'];
            $dealerAmount = $_SESSION['dealerCardsAmount'];

            if($myAmount == 21){
                $won = "my";
            }elseif($dealerAmount == 21){
                   $won = "dealer";
            }else{
                $won = "none";
            }

            $return = array( "ergebnis" => "true", "result" => $result, "dealerCards" => $dealerCards, "myCards" => $myCards, "won" => $won);

        }else{

            $return = array("ergebnis" => "false");

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
            $amount = $_SESSION['myCardsAmount'];
        }elseif($_POST['person'] == 'dealer'){
            $amount = $_SESSION['dealerCardsAmount'];
        }

        if($_POST['person'] == 'dealer' && $amount < 17 || $_POST['person'] == 'player'){
            
            $getCardReturn = $logic->getCard($cards, $amount);

            $card = $getCardReturn[0];
            $result = $getCardReturn[1];

            if($result == "over"){
                $outcome = "over";
            }elseif($result == 21){
                $outcome = "won";
            }else{
                $outcome = $result;
            }

            array_push($cards, $card);
            $_SESSION['takenCards'] = $cards;

            if($_POST['person'] == 'player'){
                $_SESSION['myCardsAmount'] = $result;
            }elseif($_POST['person'] == 'dealer'){
                $_SESSION['dealerCardsAmount'] = $result;
            }

            $return = array("card" => $card, "outcome" => $outcome);

        }else{
            $return = array("outcome" => 'cant');
        }
        $arr = array($return);

        $json = json_encode($arr);

        echo $json;

    }elseif($_POST['function'] === 'split'){

    }elseif($_POST['function'] === 'doubleDown'){

    }elseif($_POST['function'] === 'getBankAmount'){

        session_start();
        session_regenerate_id(true);

        $logic = new BlackJackGame;

        $username = $_SESSION['username'];

        $bankAmount = $logic->getBankAmount($username);

        $_SESSION['bankAmount'] = $bankAmount;

        echo $bankAmount;
    }

}

?>