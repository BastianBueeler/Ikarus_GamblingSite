<?php

session_start();
session_regenerate_id(true);

class BlackJackGame{
/*
    $MyCards = array();
    $MyCardAmount;
    $DealerCards = array();
    $DealerCardAmount;
    $inset;
    $bankAmount;
    $username; 
*/

    function setAmouont($amount){
        include("dbconnector.inc.php");
                
        if(!isset($_SESSION['bankAmount'])){
            $stmt = $mysqli->prepare("SELECT IkarusCoins FROM person WHERE username = ?");
            $stmt->bind_param("s", $_SESSION['username']);

            $stmt->execute();

            $result = $stmt->get_result();

            if($stmt->affected_rows !== 0){
            
                while($row = $result->fetch_assoc()){
                    $bankAmount = $row['IkarusCoins'];
                }
            }
        }

        if($amount <= $bankAmount){
            $_SESSION['inputIkarusCoins'] = $amount;

            $newBankAmount = $bankAmount - $amount;

            $newBankAmount;

            $username = $_SESSION['username'];

            $stmt = $mysqli->prepare("UPDATE person SET IkarusCoins = ? WHERE username = $username");

            $stmt->bind_param("i", $newBankAmount);

            $stmt->execute();
            
            return TRUE;
        }else{

            $_SESSION['bankAmount'] = $bankAmount;
            return FALSE;
        }

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