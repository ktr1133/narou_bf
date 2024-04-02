<?php

class detailSql{
    private $n; //ncode(SQLで使用する用にｼﾝｸﾞﾙｸｵﾃｰｼｮﾝで囲んでいるもの)

    public function __construct($n){
        $this -> n = $n;
    }

    public function time_span(){
        //期間別で取得日ｶﾗﾑを連想配列で返す関数
        //key：weekly value:週間, key:monthly value:月間, key:half value:半期,
        //key:yearly value:年間, key:all value:累計
        require(dirname(__FILE__).'/../database.php');
        
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
        require(dirname(__FILE__).'/../database.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $master_sql = "SELECT * FROM `ma` WHERE `ncode`=".$n.";";
        $result = $db -> query($master_sql);
        $rec = $result -> fetch();
        return $rec;
    }
    public function get_point($span){
        //引数：期間
        //特定の作品のﾎﾟｲﾝﾄﾃｰﾌﾞﾙのうち、ncodeと引数で指定した期間に応じて
        //取得日分の列を含む部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        $t = $this -> time_span();
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);

        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        if($span==='weekly'){
            $time_span_w = make_for_column($t['weekly']);
            $p_w = "SELECT `ncode`, ".$time_span_w." FROM `point` WHERE `ncode`=".$n.";";
            $result_w = $db -> query($p_w);
            $rec = $result_w -> fetch();
        }else if($span==='monthly'){
            $time_span_m = make_for_column($t['monthly']);
            $p_m = "SELECT `ncode`, ".$time_span_m." FROM `point` WHERE `ncode`=".$n.";";
            $result_m = $db -> query($p_m);
            $rec = $result_m -> fetch();
        }else if($span==='half'){
            $time_span_h = make_for_column($t['half']);
            $p_h = "SELECT `ncode`, ".$time_span_h." FROM `point` WHERE `ncode`=".$n.";";
            $result_h = $db -> query($p_h);
            $rec = $result_h -> fetch();
        }else if($span==='yearly'){
            $time_span_y = make_for_column($t['yearly']);
            $p_y = "SELECT `ncode`, ".$time_span_y." FROM `point` WHERE `ncode`=".$n.";";
            $result_y = $db -> query($p_y);
            $rec = $result_y -> fetch();
        }else if($span==='all'){
            $time_span_all = make_for_column($t['all']);
            $p_all = "SELECT `ncode`, ".$time_span_all." FROM `point` WHERE `ncode`=".$n.";";
            $result_all = $db -> query($p_all);
            $rec = $result_all -> fetch();
        }

        return $rec;
    }
    public function get_sum_p(){
        //特定の作品のﾎﾟｲﾝﾄﾃｰﾌﾞﾙのうち、ncodeと期間別の合計値の部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $sum_p_sql = "SELECT `ncode`, `sum_monthly`, `sum_half`, `sum_all`  FROM `point` WHERE `ncode`=".$n.";";
        $result = $db -> query($sum_p_sql);
        $rec = $result -> fetch();
        return $rec;
    }

    public function get_unique($span){
        //引数：期間
        //特定の作品のﾕﾆｰｸﾃｰﾌﾞﾙのうち、ncodeと引数で指定した期間に応じて
        //取得日分の列を含む部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        $t = $this -> time_span();
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        if($span==='weekly'){
            $time_span_w = make_for_column($t['weekly']);
            $u_w = "SELECT `ncode`, ".$time_span_w." FROM `unique` WHERE `ncode`=".$n.";";
            $result_w = $db -> query($u_w);
            $rec = $result_w -> fetch();
        }else if($span==='monthly'){
            $time_span_m = make_for_column($t['monthly']);
            $u_m = "SELECT `ncode`, ".$time_span_m." FROM `unique` WHERE `ncode`=".$n.";";
            $result_m = $db -> query($u_m);
            $rec = $result_m -> fetch();
        }else if($span==='half'){
            $time_span_h = make_for_column($t['half']);
            $u_h = "SELECT `ncode`, ".$time_span_h." FROM `unique` WHERE `ncode`=".$n.";";
            $result_h = $db -> query($u_h);
            $rec = $result_h -> fetch();
        }else if($span==='yearly'){
            $time_span_y = make_for_column($t['yearly']);
            $u_y = "SELECT `ncode`, ".$time_span_y." FROM `unique` WHERE `ncode`=".$n.";";
            $result_y = $db -> query($u_y);
            $rec = $result_y -> fetch();
        }else if($span==='all'){
            $time_span_all = make_for_column($t['all']);
            $u_all = "SELECT `ncode`, ".$time_span_all." FROM `unique` WHERE `ncode`=".$n.";";
            $result_all = $db -> query($u_all);
            $rec = $result_all -> fetch();
        }

        return $rec;
    }
    public function get_sum_u(){
        //特定の作品のﾕﾆｰｸﾃｰﾌﾞﾙのうち、ncodeと期間別の合計値の部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $sum_u_sql = "SELECT `ncode`, `sum_monthly`, `sum_half`, `sum_all`  FROM `unique` WHERE `ncode`=".$n.";";
        $result = $db -> query($sum_u_sql);
        $rec = $result -> fetch();
        return $rec;
    }

    public function get_mark($span){
        //引数：期間
        //特定の作品のmarkﾃｰﾌﾞﾙのうち、ncodeと引数で指定した期間に応じて
        //取得日分の列を含む部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        $t = $this -> time_span();
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        if($span==='weekly'){
            $time_span_w = make_for_column($t['weekly']);
            $mk_w = "SELECT `ncode`, ".$time_span_w." FROM `mark` WHERE `ncode`=".$n.";";
            $result_w = $db -> query($mk_w);
            $rec = $result_w -> fetch();
        }else if($span==='monthly'){
            $time_span_m = make_for_column($t['monthly']);
            $mk_m = "SELECT `ncode`, ".$time_span_m." FROM `mark` WHERE `ncode`=".$n.";";
            $result_m = $db -> query($mk_m);
            $rec = $result_m -> fetch();
        }else if($span==='half'){
            $time_span_h = make_for_column($t['half']);
            $mk_h = "SELECT `ncode`, ".$time_span_h." FROM `mark` WHERE `ncode`=".$n.";";
            $result_h = $db -> query($mk_h);
            $rec = $result_h -> fetch();
        }else if($span==='yearly'){
            $time_span_y = make_for_column($t['yearly']);
            $mk_y = "SELECT `ncode`, ".$time_span_y." FROM `mark` WHERE `ncode`=".$n.";";
            $result_y = $db -> query($mk_y);
            $rec = $result_y -> fetch();
        }else if($span==='all'){
            $time_span_all = make_for_column($t['all']);
            $mk_all = "SELECT `ncode`, ".$time_span_all." FROM `mark` WHERE `ncode`=".$n.";";
            $result_all = $db -> query($mk_all);
            $rec = $result_all -> fetch();
        }

        return $rec;
    }
    public function get_mean_mk(){
        //特定の作品のmarkﾃｰﾌﾞﾙのうち、ncodeと期間別の平均値の部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $mean_mk_sql = "SELECT `ncode`, `mean_monthly`, `mean_half`, `mean`  FROM `mark` WHERE `ncode`=".$n.";";
        $result = $db -> query($mean_mk_sql);
        $rec = $result -> fetch();
        return $rec;
    }

    public function get_calc($span){
        //引数：期間
        //特定の作品のcalcﾃｰﾌﾞﾙのうち、ncodeと引数で指定した期間に応じて
        //取得日分の列を含む部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        $t = $this -> time_span();
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        if($span==='weekly'){
            $time_span_w = make_for_column($t['weekly']);
            $c_w = "SELECT `ncode`, ".$time_span_w." FROM `calc` WHERE `ncode`=".$n.";";
            $result_w = $db -> query($c_w);
            $rec = $result_w -> fetch();
        }else if($span==='monthly'){
            $time_span_m = make_for_column($t['monthly']);
            $c_m = "SELECT `ncode`, ".$time_span_m." FROM `calc` WHERE `ncode`=".$n.";";
            $result_m = $db -> query($c_m);
            $rec = $result_m -> fetch();
        }else if($span==='half'){
            $time_span_h = make_for_column($t['half']);
            $c_h = "SELECT `ncode`, ".$time_span_h." FROM `calc` WHERE `ncode`=".$n.";";
            $result_h = $db -> query($c_h);
            $rec = $result_h -> fetch();
        }else if($span==='yearly'){
            $time_span_y = make_for_column($t['yearly']);
            $c_y = "SELECT `ncode`, ".$time_span_y." FROM `calc` WHERE `ncode`=".$n.";";
            $result_y = $db -> query($c_y);
            $rec = $result_y -> fetch();
        }else if($span==='all'){
            $time_span_all = make_for_column($t['all']);
            $c_all = "SELECT `ncode`, ".$time_span_all." FROM `calc` WHERE `ncode`=".$n.";";
            $result_all = $db -> query($c_all);
            $rec = $result_all -> fetch();
        }

        return $rec;
    }
    public function get_mean_c(){
        //特定の作品のcalcﾃｰﾌﾞﾙのうち、ncodeと期間別の平均値の部分を連想配列で返す関数
        require(dirname(__FILE__).'/../database.php');
        
        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        $mean_c_sql = "SELECT `ncode`, `mean_monthly`, `mean_half`, `mean`  FROM `calc` WHERE `ncode`=".$n.";";
        $result = $db -> query($mean_c_sql);
        $rec = $result -> fetch();
        return $rec;
    }

    public function calc_rank_mk($span){
        //markﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
        //$span: weekly or monthly or half or yearly or all
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        //週間のｶﾗﾑの指定
        $time_spans= $this -> time_span();
        $time_temp = $time_spans['weekly'];
        $time = make_for_column($time_temp);

        //sql用のncodeをphp用に戻す
        $ncode = str_replace("'", "", $n);

        //ランキングの数値指定
        if($span==='weekly'){
            $rank_w = "SELECT `ncode`, ".$time." FROM `mark` WHERE ".$time." >0 ORDER BY ".$time." ASC;";
            $result = $db -> query($rank_w);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec[$time_temp[0]] ===$before){
                  $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                  $i++;
                  $before = $rec[$time_temp[0]];
                }
            }

        }else if($span==='monthly'){
            $rank_m = "SELECT `ncode`, `mean_monthly` FROM `mark` WHERE `mean_monthly` >0 ORDER BY `mean_monthly` ASC;";
            $result = $db -> query($rank_m);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_monthly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_monthly'];
                }
            }
        }else if($span==='half'){
            $rank_h = "SELECT `ncode`, `mean_half` FROM `mark` WHERE `mean_half` >0 ORDER BY `mean_half` ASC;";
            $result = $db -> query($rank_h);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_half'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_half'];
                }
            }

        }else if($span==='yearly'){
            $rank_y = "SELECT `ncode`, `mean_yearly` FROM `mark` WHERE `mean_yearly` >0 ORDER BY `mean_yearly` ASC;";
            $result = $db -> query($rank_y);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_yearly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_yearly'];
                }
            }
        }else if($span==='all'){
            $rank_a = "SELECT `ncode`, `mean_monthly`, `mean_half`, `mean`, ".$time."  FROM `mark` ORDER BY `mean` ASC;";
            $result = $db -> query($rank_a);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean'];
                }
            }
        }else{
            $rank = null;
        }
        return $rank;
    }

    public function calc_rank_c($span){
        //calcﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
        //$span: weekly or monthly or half or yearly or all
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        //週間のｶﾗﾑの指定
        $time_spans= $this -> time_span();
        $time_temp = $time_spans['weekly'];
        $time = make_for_column($time_temp);

        //sql用のncodeをphp用に戻す
        $ncode = str_replace("'", "", $n);

        //ランキングの数値指定
        if($span==='weekly'){
            $rank_w = "SELECT `ncode`, ".$time." FROM `calc` WHERE ".$time." REGEXP '.+' ORDER BY ".$time." DESC;";
            $result = $db -> query($rank_w);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec[$time_temp[0]] ===$before){
                  $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                  $i++;
                  $before = $rec[$time_temp[0]];
                }
            }

        }else if($span==='monthly'){
            $rank_m = "SELECT `ncode`, `mean_monthly` FROM `calc` WHERE `mean_monthly` REGEXP '.+' ORDER BY `mean_monthly` DESC;";
            $result = $db -> query($rank_m);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_monthly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_monthly'];
                }
            }
        }else if($span==='half'){
            $rank_h = "SELECT `ncode`, `mean_half` FROM `calc` WHERE `mean_half` REGEXP '.+' ORDER BY `mean_half` DESC;";
            $result = $db -> query($rank_h);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_half'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_half'];
                }
            }

        }else if($span==='yearly'){
            $rank_y = "SELECT `ncode`, `mean_yearly` FROM `calc` WHERE `mean_yearly` REGEXP '.+' ORDER BY `mean_yearly` DESC;";
            $result = $db -> query($rank_y);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean_yearly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean_yearly'];
                }
            }
        }else if($span==='all'){
            $rank_a = "SELECT `ncode`, `mean` FROM `calc` WHERE `mean` REGEXP '.+' ORDER BY `mean` DESC;";
            $result = $db -> query($rank_a);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['mean'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['mean'];
                }
            }
        }else{
            $rank = null;
        }
        return $rank;
    }

    public function calc_rank_po($span){
        //pointﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
        //$span: weekly or monthly or half or yearly or all
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        //週間のｶﾗﾑの指定
        $time_spans= $this -> time_span();
        $time_temp = $time_spans['weekly'];
        $time = make_for_column($time_temp);

        //sql用のncodeをphp用に戻す
        $ncode = str_replace("'", "", $n);

        //ランキングの数値指定
        if($span==='weekly'){
            $rank_w = "SELECT `ncode`, ".$time." FROM `point` WHERE ".$time." REGEXP '.+' ORDER BY ".$time." DESC;";
            $result = $db -> query($rank_w);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec[$time_temp[0]] ===$before){
                  $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                  $i++;
                  $before = $rec[$time_temp[0]];
                }
            }

        }else if($span==='monthly'){
            $rank_m = "SELECT `ncode`, `sum_monthly` FROM `point` WHERE `sum_monthly` REGEXP '.+' ORDER BY `sum_monthly` DESC;";
            $result = $db -> query($rank_m);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_monthly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_monthly'];
                }
            }
        }else if($span==='half'){
            $rank_h = "SELECT `ncode`, `sum_half` FROM `point` WHERE `sum_half` REGEXP '.+' ORDER BY `sum_half` DESC;";
            $result = $db -> query($rank_h);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_half'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_half'];
                }
            }

        }else if($span==='yearly'){
            $rank_y = "SELECT `ncode`, `sum_yearly` FROM `point` WHERE `sum_yearly` REGEXP '.+' ORDER BY `sum_yearly` DESC;";
            $result = $db -> query($rank_y);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_yearly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_yearly'];
                }
            }
        }else if($span==='all'){
            $rank_a = "SELECT `ncode`, `sum_all` FROM `point` WHERE `sum_all` REGEXP '.+' ORDER BY `sum_all` DESC;";
            $result = $db -> query($rank_a);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_all'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_all'];
                }
            }
        }else{
            $rank = null;

        }
        return $rank;
    }

    public function calc_rank_un($span){
        //pointﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
        //$span: weekly or monthly or half or yearly or all
        require(dirname(__FILE__).'/../database.php');
        require_once(dirname(__FILE__).'/../functions/make_for_column.php');

        $n = $this -> n;
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };

        //週間のｶﾗﾑの指定
        $time_spans= $this -> time_span();
        $time_temp = $time_spans['weekly'];
        $time = make_for_column($time_temp);

        //sql用のncodeをphp用に戻す
        $ncode = str_replace("'", "", $n);

        //ランキングの数値指定
        if($span==='weekly'){
            $rank_w = "SELECT `ncode`, ".$time." FROM `unique` WHERE ".$time." REGEXP '.+' ORDER BY ".$time." DESC;";
            $result = $db -> query($rank_w);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec[$time_temp[0]] ===$before){
                  $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                  $i++;
                  $before = $rec[$time_temp[0]];
                }
            }

        }else if($span==='monthly'){
            $rank_m = "SELECT `ncode`, `sum_monthly` FROM `unique` WHERE `sum_monthly` REGEXP '.+' ORDER BY `sum_monthly` DESC;";
            $result = $db -> query($rank_m);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_monthly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_monthly'];
                }
            }
        }else if($span==='half'){
            $rank_h = "SELECT `ncode`, `sum_half` FROM `unique` WHERE `sum_half` REGEXP '.+' ORDER BY `sum_half` DESC;";
            $result = $db -> query($rank_h);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_half'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_half'];
                }
            }

        }else if($span==='yearly'){
            $rank_y = "SELECT `ncode`, `sum_yearly` FROM `unique` WHERE `sum_yearly` REGEXP '.+' ORDER BY `sum_yearly` DESC;";
            $result = $db -> query($rank_y);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_yearly'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_yearly'];
                }
            }
        }else if($span==='all'){
            $rank_a = "SELECT `ncode`, `sum_all` FROM `unique` WHERE `sum_all` REGEXP '.+' ORDER BY `sum_all` DESC;";
            $result = $db -> query($rank_a);
            $i=1;
            $before = 999999999;
            while($rec = $result -> fetch()){
                if($rec['sum_all'] ===$before){
                    $i = $i -1;
                }
                if($rec['ncode']===$ncode){
                    $rank = $i;
                    break;
                }else{
                    $i++;
                    $before = $rec['sum_all'];
                }
            }
        }else{
            $rank= null;
        }
        return $rank;
    }
}

?>