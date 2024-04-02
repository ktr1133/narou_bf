<?php

class writerDetailSql{
    private $w;//sql用にｼﾝｸﾞﾙｸｵﾃｰｼｮﾝを付けた作者名

    public function __construct($w){
        $this -> w = $w;
    }

    public function time_span(){
        //期間別で取得日ｶﾗﾑを連想配列で返す関数
        //key：weekly value:週間, key:monthly value:月間, key:half value:半期,
        //key:yearly value:年間, key:all value:累計
        require_once(dirname(__FILE__).'/../database.php');
        
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        $sql = "SHOW COLUMNS FROM `mark`";
        $dataTable = $db -> query($sql);
        $date_array = array();
        while($rec =  $dataTable ->fetch()):
        if (false !== strpos($rec['Field'], '-')){
            array_push($date_array, $rec['Field']);
        };
        endwhile;

        $time_span_w = [];
        for($i=1; $i<2; $i++):
            array_push($time_span_w, $date_array[count($date_array) - $i]);
        endfor;
        $time_span_m = [];
        for($i=1; $i<6; $i++):
            array_push($time_span_m, $date_array[count($date_array) - $i]);
        endfor;
        $time_span_h = [];
        for($i=1; $i<27; $i++):
            array_push($time_span_h, $date_array[count($date_array) - $i]);
        endfor;
        $time_span_y = [];
        for($i=1; $i<53; $i++):
            array_push($time_span_y, $date_array[count($date_array) - $i]);
        endfor;
        $time_span_all = [];
        for($i=1; $i<count($date_array); $i++):
            array_push($time_span_all, $date_array[count($date_array) - $i]);
        endfor;
        return ['weekly' => $time_span_w, 'monthly' => $time_span_m, 'half' => $time_span_h, 'yearly' => $time_span_y, 'all' => $time_span_all];
    }


    public function get_master(){
        //特定の作品のﾏｽﾀｰﾃｰﾌﾞﾙ情報を連想配列で返す関数
        require_once(dirname(__FILE__).'/../database.php');

        $w = $this -> w;//sql用にｼﾝｸﾞﾙｸｵﾃｰｼｮﾝを付けた作者名
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $master_sql = "SELECT * FROM `ma` WHERE `writer`=".$w.";";
        $result = $db -> query($master_sql);
        return $result;
    }
}

?>