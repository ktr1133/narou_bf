<?php
class PrepareSQL{
    private $c;//category
    private $t;//time_span
    private $gan;//type:array general_all_no_from & general_all_no_to
    private $uf;//update_frequency

    public function __construct($c, $t, $gan, $uf){
        $this -> c = $c;
        $this -> t = $t;
        $this -> gan = $gan;
        $this -> uf = $uf;
    }

    public function category(){
        //選択した種類に応じて必要なﾃｰﾌﾞﾙを返す
        if(isset($this -> c)){
            $c = $this -> c;
            if($c ==='lowPHighU'){
                $cate='mark';
            }else if($c==='lowPHighUHighF'){
                $cate='calc';
            }else if($c==='HighP'){
                $cate='point';
            }else if($c==='HighU'){
                $cate='unique';
            }else if($c==='HighR'){
                $cate='ma';
            }
        }
        return ['cate' => $cate];
    }
    public function time_span(){
        //選択した期間に含まる分の列ﾃﾞｰﾀを配列で返す
        require_once(dirname(__FILE__).'/database.php');

        if(isset($this -> t)){
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
            while($rec =  $dataTable -> fetch()):
            if (false !== strpos($rec['Field'], '-')){
                array_push($date_array, $rec['Field']);
            };
            endwhile;

            $t = $this -> t;
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

    public function general_all_no(){
        //入力した数値をSQL用の配列で返す
        if(isset($this -> gan)){
            $gan = $this -> gan;
            if(isset($gan['gan_from'])){
                $gan_from = $gan['gan_from'];
            }else{
                $gan_from = null;
            }
            if(isset($gan['gan_to'])){
                $gan_to = $gan['gan_to'];
            }else{
                $gan_to = null;
            };
        }
        return ['gan_from' => $gan_from, 'gan_to' => $gan_to];
    }

    public function update_frequency(){
        //選択した値をSQL用のｺｰﾄﾞで返す
        if(isset($this -> uf)){
            $uf = $this -> uf;
            $perM = 1 / 30;
            $perW = 1 / 7;
            $perD = 1;
            if($uf==='morePerM'){
                $uf_value = $perM;
            }else if($uf==='morePerW'){
                $uf_value = $perW;
            }else if($uf==='morePerD'){
                $uf_value = $perD;
            }
        }
        return ['update_frequency' => $uf_value];
    }

};

?>