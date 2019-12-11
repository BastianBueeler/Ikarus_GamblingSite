<?php

class BlackJackGame{


    function setAmount($amount, $bankAmount, $username){
        include("dbconnector.inc.php");

        $amount = strval($amount);

        if($amount <= $bankAmount){

            $newBankAmount = $bankAmount - $amount;

            $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

            $stmt->bind_param("is", $newBankAmount, $username);

            $stmt->execute();
            
            return $newBankAmount;
        }else{
            return '';
        }

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

    function getCard($takenCards, $cardsAmount){
        
        $cardValueArray = [
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
    
        $cardArtArray = [
            "herz",
            "ecke",
            "kreuz",
            "schufle",
        ];
        
        do{
            $goOn = FALSE;

            $cardValueArray = array_values($cardValueArray);
            $cardArtArray = array_values($cardArtArray);

            $cardValue = rand(0, 12);
            $cardArt = rand(0, 3);

            $card = $cardArtArray[$cardArt] . $cardValueArray[$cardValue];
            if(sizeof($takenCards) !== 0){
                for($i = 0; $i < sizeof($takenCards); $i++){
                    if(strcmp($takenCards[$i], $card) == 0){
                        $goOn = TRUE;
                    }
                }
            }
        }while($goOn);

        if($cardValue > 7 && $cardValue < 12){
            $cardsAmount += 10;
        }elseif($cardValue == 12){
            $cardsAmount += 11;
            if($cardsAmount > 21){
                $cardsAmount -= 11;
                $cardsAmount += 1;
            }
        }else{
            $cardsAmount += $cardValue + 2;
        }

        if($cardsAmount > 21){
            $return = [
                $card,
                "over",
            ];
        }else{
            $return = [
                $card,
                $cardsAmount,
            ];
        }

        return $return;
    }
/*   

    function doubleDown(){

    }

    function split(){

    }
*/
}
?>