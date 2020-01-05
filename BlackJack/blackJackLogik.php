<?php

class BlackJackGame{


    function setAmount($amount, $bankAmount, $username){
        include("../dbconnector.inc.php");

        $amount = strval($amount);

        $betPremission = [];
        //überprüfen ob einsatz nicht grösser als vermögen ist
        if($amount <= $bankAmount){

            $newBankAmount = $bankAmount - $amount;
            //neues vermögen in der db speichern 
            $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

            $stmt->bind_param("is", $newBankAmount, $username);

            $stmt->execute();

            $betPremission['premission'] = true;
            $betPremission['newBankAmount'] = $newBankAmount;

        }else{

            $betPremission['premission'] = false;

        }
        //rückgabe ob einsatz möglich ist und neuse vermögen
        return $betPremission;

    }

    function getBankAmount($username){
        //abfragen des vermögens
        include("../dbconnector.inc.php");
        
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

    function getCard($takenCards, $cardsOfPerson){
        
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
        //zufällig eine karte selektieren
        //solange bis eine karte selektiert wird, welche noch nicht gezogen wurde
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

        //wert karten zusammenrechnen
        $cardsWorth = $this->addUpCards($cardsOfPerson, $card);

        $currentStatusOfCards = [
            "card"       => $card,
            "cardsWorth" => $cardsWorth,
        ];
        //rückgabe der wert der karten und die karte
        return $currentStatusOfCards;
    }

    function addUpCards($cards, $currentlyTakenCard){
        //karten zusammen rechnen (Ass als 11 gezählt)
        $cardsWorth = $this->getWorthOfCardValue($currentlyTakenCard);

        foreach($cards as &$card){
            $cardsWorth += $this->getWorthOfCardValue($card);
        }

        if($cardsWorth > 21){
            //falls über 21 schauen ob Ass dabei ist
            foreach($cards as &$card){
                if(strpos($card, 'Ass') >= 0){
                    $amountOfAss += 1;
                }
            }

            //falls eine oder mehere Ass dabei sind, eine nach der anderen als 1 zählen und schauen ob man unter 21 kommt
            for($i = 1; $i <= $amountOfAss; $i++){
                $cardsWorth -= 10;
                if($cardsWorth <= 21){
                    continue;
                }
            }
        }
        //karten wert zurückgeben
        return $cardsWorth;
    }

    function getWorthOfCardValue($card){
        //karte zu einem wert zugewiesen zurückgegeben
        switch(true){
            case strpos($card, '2'):
                $cardWorth = 2;
                break;
            case strpos($card, '3'):
                $cardWorth = 3;
                break;
            case strpos($card, '4'):
                $cardWorth = 4;
                break;
            case strpos($card, '5'):
                $cardWorth = 5;
                break;
            case strpos($card, '6'):
                $cardWorth = 6;
                break;
            case strpos($card, '7'):
                $cardWorth = 7;
                break;
            case strpos($card, '8'):
                $cardWorth = 8;
                break;
            case strpos($card, '9'):
                $cardWorth = 9;
                break;
            case strpos($card, '10'):
                $cardWorth = 10;
                break;
            case strpos($card, 'Bauer'):
                $cardWorth = 10;
                break;
            case strpos($card, 'Königin'):
                $cardWorth = 10;
                break;
            case strpos($card, 'König'):
                $cardWorth = 10;
                break;
            case strpos($card, 'Ass'):
                $cardWorth = 11;
                break;   
        }
        return $cardWorth; 
    }

    function multiplyBet($multiplier, $bet, $bankAmount, $username){
        //einsatz wird multipliziert und in db geschrieben
        include("../dbconnector.inc.php");

        $betMultiplyed = bcmul($multiplier, $bet);

        $newBankAmount = $betMultiplyed + $bankAmount;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

        $stmt->bind_param("is", $newBankAmount, $username);

        $stmt->execute();

        $return = [
            "newBankAmount" => $newBankAmount,
            "moneyGetBack" => $betMultiplyed,
        ];
        //rückgabe von neuem vermögen und wie viel geld man erhält            
        return $return;

    }

    function getBetBack($bet, $bankAmount, $username){
        //bei einem unentschieden den einsatz zurückerhalten (in db geschrieben)
        include("../dbconnector.inc.php");

        $newBankAmount = $bet + $bankAmount;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = ?");

        $stmt->bind_param("is", $newBankAmount, $username);

        $stmt->execute();

        $return = [
            "newBankAmount" => $newBankAmount,
            "moneyGetBack" => $bet,
        ];
        //rückgabe von neuem vermögen und wie viel geld man erhält
        return $return;
    }
}
?>