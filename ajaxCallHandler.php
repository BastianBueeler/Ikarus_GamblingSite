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

        $amount = strval($_POST['value']);

        //echo $logic->setAmouont($amount);
        
        if($logic->setAmouont($amount)){

            $return = array("true" => "ergebnis");
            $return = array_flip($return);
            //$return = array("Dario" => "name", "Grob" => "nachname", "17" => "alter")
            //$return = array_flip($test);
            //echo "true";

        }else{

            $return = array("false" => "ergebnis");
            $return = array_flip($return);
            //$return = array("Dario" => "name", "Grob" => "nachname", "17" => "alter");
            //$return = array_flip($test);
            //echo "false";
        }
        
        $arr = array($return);

        $json = json_encode($arr);

        echo $json;
        
        
        /*
        $test = array("Dario" => "name", "Grob" => "nachname", "17" => "alter");
        $test = array_flip($test);

        $myArr = array($test);
        $myJSON = json_encode($myArr);
    
        echo $myJSON;
        */

    }elseif($_POST['function'] === 'takeCard'){

    }elseif($_POST['function'] === 'split'){

    }elseif($_POST['function'] === 'doubleDown'){

    }

}




?>