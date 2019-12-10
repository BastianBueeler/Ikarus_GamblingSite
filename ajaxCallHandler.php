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
            
            array_push($cards, $logic->getCard($cards));
            array_push($cards, $logic->getCard($cards));
            array_push($cards, $logic->getCard($cards));
            array_push($cards, $logic->getCard($cards));

            $dealerCards = [
                $cards[0],
                $cards[1],
            ];

            $myCards = [
                $cards[2],
                $cards[3],
            ];
            
            $return = array( "ergebnis" => "true", "result" => $result, "dealerCards" => $dealerCards, "myCards" => $myCards);

        }else{

            $return = array("ergebnis" => "false");

        }
        
        $arr = array($return);

        $json = json_encode($arr);

        echo $json;

    }elseif($_POST['function'] === 'takeCard'){

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