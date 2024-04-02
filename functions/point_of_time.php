<?php
function pick_up_year(){
    //ﾃﾞｰﾀﾍﾞｰｽから取得「年」を取り出す関数
    require(dirname(__FILE__).'/../database.php');

    try{
        // 接続
        $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
    } catch(PDOException $e){
        echo '<br>dbname: '.$dbname.'<br>';
        echo "データベース接続失敗" . PHP_EOL."<br>";
        echo $e->getMessage();
        exit;
    };

    $sql_get_time = "SHOW COLUMNS FROM `mark`";
    $date_columns = $db -> query($sql_get_time);
    $year_temp = [];
    while($rec =  $date_columns ->fetch()):
    if (false !== strpos($rec['Field'], '-')){
        $date_temp01 = explode(' ', $rec['Field']);
        $date_temp02 = explode('-', $date_temp01[0]);
        $year_temp[] = $date_temp02[0];
    };
    endwhile;
    $year = array_unique($year_temp);
    return $year;
};
function pick_up_month($y){
    //ﾃﾞｰﾀﾍﾞｰｽから取得「月」を取り出す関数
    //$y = $_GET['p-year']
    require(dirname(__FILE__).'/../database.php');

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

    $months = [];
    for($i=0; $i<count($date_array); $i++){
        if(false !== strpos($date_array[$i], $y)){
            $date_temp01 = explode(' ', $rec['Field']);
            $date_temp02 = explode('-', $date_temp01[0]);
            $month_temp = $date_temp02[1];
            $months[] = $month_temp;
        }
    }
    return [$y, $months];
}
function pick_up_day($y, $m){
    //ﾃﾞｰﾀﾍﾞｰｽから取得「日」を取り出す関数
    //$y = $_GET['p-year']
    require(dirname(__FILE__).'/../database.php');

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

    $days = [];
    for($i=0; $i<count($date_array); $i++){
        if(false !== strpos($date_array[$i], $y)){
            if(false !== strpos($date_array[$i], $m)){
                $date_temp01 = explode(' ', $rec['Field']);
                $date_temp02 = explode('-', $date_temp01[0]);
                $day_temp = $date_temp02[2];
                $days[] = $day_temp;
            }
        }
    }
    return [$y, $m, $days];
}


?>