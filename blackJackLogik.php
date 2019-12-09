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

        $bankAmount = 'test';
        
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

    function getCard($takenCards){
        $cardValue = array{
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
        };

        $cardArt = array{
            "herz",
            "ecke",
            "kreuz",
            "schufle",
        };

        
    }
/*
    function getMyCard(){
        
        $MyCardValue = rand(2, 14);
        $MyCardArt = rand(1, 4);

        switch ($MyCardArt) {
            case 1:
                $card = $MyCardValue . "H";
                array_push($MyCards, $card);
                break;
            
            case 2:
                $card = $MyCardValue . "D";
                array_push($MyCards, $card);
                break;
                
            case 3:
                $card = $MyCardValue . "S";
                array_push($MyCards, $card);
                break;
                
            case 4:
                $card = $MyCardValue . "C";
                array_push($MyCards, $card);
                break;

            default:
                # code...
                break;

            if($MyCardValue >= 10 && $MyCardValue < 14){ 
                
                $MyCardAmount += 10;

            }elseif($MyCardValue < 10){
                
                $MyCardAmount += $MyCardValue;
                
            }elseif
        }

    }

    function getDealerCard(){
        $MyCardValue = rand(2, 14);
        $MyCardArt = rand(1, 4);

        switch ($MyCardArt) {
            case 1:
                $card = $MyCardValue . "H";
                array_push($MyCards, $card);
                break;
            
            case 2:
                $card = $MyCardValue . "D";
                array_push($MyCards, $card);
                break;
                
            case 3:
                $card = $MyCardValue . "S";
                array_push($MyCards, $card);
                break;
                
            case 4:
                $card = $MyCardValue . "C";
                array_push($MyCards, $card);
                break;

            default:
                # code...
                break;
        }
    }

    function win($multiplier){
        $winAmount = $inset * $multiplier;
        $insertIkarusCoins = $winAmount + $bankAmount;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = $insertIkarusCoins WHERE username=?");

        $stmt->bind_param("s", $username);

        $stmt->execute();

    }

    function loos(){
        $insertIkarusCoins = $bankAmount - $inset;

        $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = $insertIkarusCoins WHERE username=?");

        $stmt->bind_param("s", $username);

        $stmt->execute();
    }

    function doubleDown(){

    }

    function split(){

    }

    function compareCardeValuesWith21($player){
        if(strcmp($player, "user")){

            

        }elseif(strcmp($player, "dealer")){



        }
    }

    function closeDBconnection(){
        
        if(empty($stmt)){

        }else{
            $stmt->close();
        }

        $mysqli->close();

    }
*/
}
?>