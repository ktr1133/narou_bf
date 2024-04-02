<?php

function make_for_column($t){
    //$t: make_time_span関数の返値
    //make_time_span関数で作成した配列をsqlで使用できるよう加工し返す関数
    $temp = [];
    foreach($t as $ele){
        $temp[] = "`".$ele."`";
    }
    $sql = implode(', ', $temp);
    return $sql;
}


?>