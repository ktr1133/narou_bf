<?php
function make_time_span($t){
    //$t = $_GET['time_span']
    //選択した期間に含まる分の日付ﾃﾞｰﾀを配列で返す
    require(dirname(__FILE__).'/../database.php');

    if(isset($t)){
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo '<br>dbname: '.$dbname.'<br>';
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        $sql = "SHOW COLUMNS FROM `mark`";
        $dataTable = $db -> query($sql);
        $date_array = array();
        while($rec =  $dataTable -> fetch()):
        if (false !== strpos($rec['Field'], '-')){
            array_push($date_array, $rec['Field']);
        };
        endwhile;

        $time_span = [];
        if($t==='weekly'){
            for($i=1; $i<2; $i++):
                array_push($time_span, $date_array[count($date_array) - $i]);
            endfor;
        }else if($t==='monthly'){
            for($i=1; $i<6; $i++):
                array_push($time_span, $date_array[count($date_array) - $i]);
            endfor;
        }else if($t==='half'){
            for($i=1; $i<27; $i++):
                array_push($time_span, $date_array[count($date_array) - $i]);
            endfor;
        }else if($t==='yearly'){
            for($i=1; $i<53; $i++):
                array_push($time_span, $date_array[count($date_array) - $i]);
            endfor;
        }else if($t==='all'){
            for($i=1; $i<count($date_array); $i++):
                array_push($time_span, $date_array[count($date_array) - $i]);
            endfor;
        }
    };
    return $time_span;
}

?>