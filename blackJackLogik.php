<?php

class BlackJackGame{


    function setAmount($amount, $bankAmount, $username){
        include("dbconnector.inc.php");

        $amount = strval($amount);

        $betPremission = [];

        if($amount <= $bankAmount){

            $newBankAmount = $bankAmount - $amount;

            $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

            $stmt->bind_param("is", $newBankAmount, $username);

            $stmt->execute();

            $betPremission['premission'] = true;
            $betPremission['newBankAmount'] = $newBankAmount;

        }else{

            $betPremission['premission'] = false;

        }

        return $betPremission;

    }

    function getBankAmount($username){

        include("dbconnector.inc.php");
        
        $stmt = $mysqli->prepare("SELECT IkarusCoins FROM person WHERE username = ?");
        
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if($stmt->affected_rows !== 0){
            while($row = $result->fetch_assoc()){
                $bankAmount = $row['IkarusCoins'];
            }
        }

        return $bankAmount;
    }

    function getCard($takenCards, $cardsWorth){
        
        $cardsValuesArray = [
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "10",
            "Bauer",
            "Königin",
            "König",
            "Ass",
        ];
    
        $cardsSymbolsArray = [
            "herz",
            "ecke",
            "kreuz",
            "schufle",
        ];
        
        do{
            $goOn = FALSE;

            $randCardValue = rand(0, 12);
            $randCardArt = rand(0, 3);

            $card = $cardsSymbolsArray[$randCardArt] . $cardsValuesArray[$randCardValue];
            if(sizeof($takenCards) !== 0){
                for($i = 0; $i < sizeof($takenCards); $i++){
                    if($takenCards[$i] == $card){
                        $goOn = true;
                    }
                }
            }
        }while($goOn);

        if($randCardValue > 7 && $randCardValue < 12){
            $cardsWorth += 10;
        }elseif($randCardValue == 12){
            $cardsWorth += 11;
            if($cardsWorth > 21){
                $cardsWorth -= 11;
                $cardsWorth += 1;
            }
        }else{
            $cardsWorth += $randCardValue + 2;
        }

        $currentStatusOfCards = [
            "card"       => $card,
            "cardsWorth" => $cardsWorth,
        ];

        return $currentStatusOfCards;
    }

    function multiplyBet($multiplier, $bet, $bankAmount, $username){

        include("dbconnector.inc.php");

        $betMultiplyed = bcmul($multiplier, $bet);

        $newBankAmount = $betMultiplyed + $bankAmount;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

        $stmt->bind_param("is", $newBankAmount, $username);

        $stmt->execute();

        $return = [
            "newBankAmount" => $newBankAmount,
            "moneyGetBack" => $betMultiplyed,
        ];
            
        return $return;

    }

    function getBetBack($bet, $bankAmount, $username){

        include("dbconnector.inc.php");

        $newBankAmount = $bet + $bankAmount;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

        $stmt->bind_param("is", $newBankAmount, $username);

        $stmt->execute();

        $return = [
            "newBankAmount" => $newBankAmount,
            "moneyGetBack" => $bet,
        ];

        return $return;

    }
}
?>