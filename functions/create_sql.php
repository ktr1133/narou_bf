<?php
//createページのSQL生成用ﾒｿｯﾄﾞ
function createSQL($c, $t, $gan, $uf){
  //$c: $_GET['cate'], $t: $_GET['time_span']
  //$gan: $_GET['gan_num'], $uf: $_GET['frequency']
  require_once(dirname(__FILE__).'/../classes/prepare_sql.php');
  require_once(dirname(__FILE__).'/make_time_span.php');

    //入力情報をSQL文に加工して配列に格納
    $paramsClass = new PrepareSQL($c, $t, $gan, $uf);

    $tables=[];
    //sqlで使用するﾃｰﾌﾞﾙの指定
    if(!empty($uf)){
      $tables[] = 'update_frequency';
    }
    //利用するﾃｰﾌﾞﾙの紐づけ(ﾏｽﾀｰと必須ﾃｰﾌﾞﾙ)
    if(isset($tables)){
        $tables_temp = [];
        foreach($tables as $t_ele){
          if($t_ele==='update_frequency'){
            $tbl =  "`".$t_ele."`";
            $tables_temp[] = $tbl;
            $ele = "`ma`.`ncode` = ".$tbl.".`ncode`";
            $where[] = $ele;
          }
        }
        $cate = $paramsClass -> category();
        $requiredTable = $cate['cate'];
        $requiredLink = '`ma`.`ncode`=`'.$requiredTable.'`.`ncode`';
    }
    //種類と期間によって変わるｶﾗﾑを特定
    $cate = $paramsClass -> category();
    if(!empty($cate)){
      if($cate['cate']==='mark'){
          if(!empty($t)){
            if($t==='monthly'){
              $order_by_basic = 'mean_monthly';
            }else if($t==='half'){
              $order_by_basic = 'mean_half';
            }else if($t==='yearly'){
              $order_by_basic = 'mean_yearly';
            }else if($t==='all'){
              $order_by_basic = 'mean';
            }else if($t==='weekly'){
              $time_spans = make_time_span($t);
              $weekly = $time_spans[0];
              $order_by_basic = $weekly;
            }
          }
        }else if($cate['cate']==='calc'){
            if(!empty($t)){
                if($t==='monthly'){
                  $order_by_basic = 'mean_monthly';
                }else if($t==='half'){
                  $order_by_basic = 'mean_half';
                }else if($t=== 'yearly'){
                  $order_by_basic = 'mean_yearly';
                }else if($t==='all'){
                  $order_by_basic = 'mean';
                }else if($t==='weekly'){
                  $time_spans = make_time_span($t);
                  $weekly = $time_spans[0];
                  $order_by_basic = $weekly;
                }
              }else{
                $time_spans = make_time_span($t);
                $weekly = $time_spans[0];
                $order_by_basic = $weekly;
              }
        }else if($cate['cate']==='point'){
          if(!empty($t)){
            if($t==='monthly'){
              $order_by_basic = 'sum_monthly';
            }else if($t==='half'){
              $order_by_basic = 'sum_half';
            }else if($t=== 'yearly'){
              $order_by_basic = 'sum_yearly';
            }else if($t==='all'){
              $order_by_basic = 'sum_all';
            }else if($t==='weekly'){
              $time_spans = make_time_span($t);
              $weekly = $time_spans[0];
              $order_by_basic = $weekly;
            }
          }else{
            $time_spans = make_time_span($t);
            $weekly = $time_spans[0];
            $order_by_basic = $weekly;
          }
      }else if($cate['cate']==='unique'){
        if(!empty($t)){
            if($t==='monthly'){
              $order_by_basic = 'sum_monthly';
            }else if($t==='half'){
              $order_by_basic = 'sum_half';
            }else if($t=== 'yearly'){
              $order_by_basic = 'sum_yearly';
            }else if($t==='all'){
              $order_by_basic = 'sum_all';
            }else if($t==='weekly'){
              $time_spans = make_time_span($t);
              $weekly = $time_spans[0];
              $order_by_basic = $weekly;
            }
          }else{
            $time_spans = make_time_span($t);
            $weekly = $time_spans[0];
            $order_by_basic = $weekly;
          }
      }
    }
    
    //種類を配列に格納し、種類によって変わるorder byの順序を変数に格納
    if(!empty($c)){
        $cate = $paramsClass -> category();
        if($cate['cate'] ==='mark'){
            $order = 'ASC';
            $filter = "AND `".$requiredTable."`.`".$order_by_basic."` > 0";
        }else if($cate['cate'] ==='calc'){
            $order = 'DESC';
            $filter = '';
        }else if($cate['cate']==='point'){
            $order = 'DESC';
            $filter = '';
        }else if($cate['cate']==='unique'){
            $order = 'DESC';
            $filter = '';
        };
    }
    
    //総話数を配列に格納、総話数の入力があった場合は、配列のキー値：ﾃｰﾌﾞﾙにマスターを格納
    $where = [];
    if(!empty($gan)){
        $gan = $paramsClass -> general_all_no();
        if(!empty($gan['gan_from'])):
            $where['general_all_no_from'] = '`b`.`general_all_no` >= '.$gan['gan_from'];
        endif;
        if(!empty($gan['gan_to'])):
            $where['general_all_no_to'] = '`b`.`general_all_no` <= '.$gan['gan_to'];
        endif;
    }
    //期間によって使用する更新頻度に関する指標を変える
    if(!empty($uf)){
        $uf = $paramsClass -> update_frequency();
      if($t==='monthly'){
          $mean_uf = 'mean_monthly';
      }else if($t==='half'){
          $mean_uf = 'mean_half';
      }else if($t==='mean_yearly'){
          $mean_uf = 'mean_yearly';
      }else if($t==='all'){
          $mean_uf = 'mean';
      }else{
          $time_spans = make_time_span($t);
          $weekly = $time_spans[0];
          $mean_uf = $weekly;
      }
      $tbl_uf = '`update_frequency`.`'.$mean_uf.'`';
      $where_uf = ' AND `update_frequency`.`'.$mean_uf.'` >= '.$uf['update_frequency'];
    }

    
    //配列に格納したSQL文を結合
    $sql_basic_temp = "SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
           `".$requiredTable."`.`".$order_by_basic."` AS `ave` FROM `ma`, `".$requiredTable."`
            WHERE ".$requiredLink.$filter;
    $basicTbl = '( '.$sql_basic_temp.' ) AS `b`';
    
    if(!empty($where)){
        if(empty($where['general_all_no_from'])){
          $whereSQL = $where['general_all_no_to'];
        }elseif(empty($where['general_all_no_to'])){
          $whereSQL = $where['general_all_no_from'];
        }else{
          $whereSQL = implode(' AND ', $where);
        };
        $tableSQL = implode(' , ', $tables_temp);
        $linkSQL = '`b`.ncode='.$tables_temp[0].'.`ncode`';
        if(isset($where_uf)){
          $sql = 'SELECT `b`.`ncode`, `b`.`title`, `b`.`writer`, `b`.`ave`,'.$tbl_uf.' FROM '.$basicTbl.' , '.$tableSQL.' WHERE '.$linkSQL.' AND '.$whereSQL.$where_uf.' ORDER BY `b`.`ave` '.$order.' LIMIT 50;';
        }else{
          $sql = 'SELECT `b`.`ncode`, `b`.`title`, `b`.`writer`, `b`.`ave` FROM '.$basicTbl.' , '.$tableSQL.' WHERE '.$linkSQL.' AND '.$whereSQL.' ORDER BY `b`.`ave` '.$order.' LIMIT 50;';
        }
    }else{
      $sql = 'SELECT `b`.`ncode`, `b`.`title`, `b`.`writer`, `b`.`ave` FROM '.$basicTbl.' ORDER BY `b`.`ave` '.$order.' LIMIT 50;';
    }
    return $sql;
}
?>