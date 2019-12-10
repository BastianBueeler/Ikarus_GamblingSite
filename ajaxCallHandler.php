<?php
/*
if(isset($_POST['name'])){
    if($_POST['name'] === 'dario'){

        $test = array("Dario" => "name", "Grob" => "nachname", "17" => "alter");
        $test = array_flip($test);

    }elseif($_POST['name'] === 'basti'){
        $test = array("Basti" => "name", "Bueeler" => "nachname", "18" => "alter");
        $test = array_flip($test);
    }elseif($_POST['name'] === 'dome'){
        $test = array("Dome" => "name", "Luder" => "nachname", "18" => "alter");
        $test = array_flip($test);
    }

    $myArr = array($test);
    $myJSON = json_encode($myArr);

    echo $myJSON;
}
*/

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
            $return = array( "true" => "ergebnis", $result => "result");
            $return = array_flip($return);

        }else{

            $return = array("false" => "ergebnis");
            $return = array_flip($return);

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