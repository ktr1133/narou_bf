<?php
require_once(dirname(__FILE__).'/database.php');
require_once('../set.php');


$w = "'".$_GET['writer']."'";
$writer = $_GET['writer'];

try{
  // 接続
  $db = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);

} catch(PDOException $e){
    echo "データベース接続失敗" . PHP_EOL."<br>";
  echo $e->getMessage();
  exit;
};

//日付ﾃﾞｰﾀの取得
$sql = "SHOW COLUMNS FROM `mark`";
$dataTable = $db -> query($sql);
$date_array = array();
while($rec =  $dataTable -> fetch()):
if (false !== strpos($rec['Field'], '-')){
    array_push($date_array, $rec['Field']);
};
endwhile;

$last_update_temp = $date_array[count($date_array) - 1];
$array = preg_split("/\s/", $last_update_temp);
$last_update_temp02 = $array[0];
$last_update = preg_split('/-/', $last_update_temp02);

//作者ﾃﾞｰﾀ取得
$master_sql = "SELECT * FROM `ma` WHERE `writer`=".$w." ORDER BY `sum_un`;";
$result = $db -> query($master_sql);
$represent_sql = "SELECT *,COUNT(`writer`) AS `count`, SUM(`sum_po`) AS `po`, SUM(`sum_un`) AS `un` FROM `ma` WHERE `writer`=".$w.";";
$result_rep = $db -> query($represent_sql);
$rec_rep = $result_rep -> fetch();

//chart用共通
$sql_forChart = "SELECT * FROM `ma` WHERE `writer`= ".$w." ORDER BY `sum_un`;";
$result_forChart = $db -> query($sql_forChart);

$ncodes = [];
$dates = [];
$titles = [];
$mks = [];
$gans = [];
$ufs = [];
$lastups = [];
$calcs = [];
$pos = [];
$uns = [];

foreach($result as $rec){
  $ncodes[] = $rec['ncode'];
  $temp[] = $rec['last_get_date'];
  $titles[] = $rec['title'];
  $mks[] = $rec['mean_mk'];
  $gans[] = $rec['general_all_no'];
  $ufs[] = $rec['mean_uf'];
  $lastups[] = $rec['general_lastup'];
  $calcs[] = $rec['mean_c'];
  $pos[] = $rec['sum_po'];
  $uns[] = $rec['sum_un'];
};


//ｸﾞﾗﾌ描画用ﾃﾞｰﾀ取得
$sql_count = "SELECT *,COUNT(`ncode`) AS `count` FROM `ma` WHERE `writer`=".$w.";";
$sql_max = "SELECT *,MAX(`sum_po`) AS `po_max` FROM `ma` WHERE `writer`=".$w.";";
$result_count = $db -> query($sql_count);
$result_max = $db -> query($sql_max);
$rec_count = $result_count -> fetch();
$rec_max = $result_max -> fetch();
$count = $rec_count['count'];
$max = $rec_max['po_max'];

if($max>=10000000){
  $scale = 1/round($max/100, -5);
}else if($max>=1000000){
  $scale = 1/round($max/100, -4);
}else if($max>=100000){
  $scale = 1/round($max/100, -3);
}else if($max>=10000){
  $scale = 1/round($max/100, -2);
}else if($max>=1000){
  $scale = 1/round($max/100, -1);
}else{
  $scale = 1/round($max/100, 0);
};


?>
<!doctype html>
<html lang="jp">
  <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L279PCKJGD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-L279PCKJGD');
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@dehonjo" />
    <meta property="og:url" content="https://politicalactivityanalysis.com/" />
    <meta property="og:title" content="Making Politics More Accessible" />
    <meta property="og:description" content="なろう, 良作, ランキング, 探す" />
    <meta property="og:image" content="https://politicalactivityanalysis.com/img/twitter_card.png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../novel_icon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">


    <title>Search Narou Works</title>
  </head>
  <body>
    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    <?php require_once('../PHPparts/nav_2nd.php')?>

    <ol>
    <div class="container clear">
      <li><a href="https://freenovelanalysis.com/">Home</a></li>
      <li><a href="https://freenovelanalysis.com/search.php">Create</a></li>
      <li><a href="https://freenovelanalysis.com/search/result_w.php">Create Ranking By Writers</a></li>
      <li>Detail Writer</li>
    </div>
  </ol>

    <section class="detail-dw">
      <div class="detail-title">
        <?php echo $rec_rep['writer']?>
      </div>
      <div class="container clear">
        <div class="update">
          Last Up Date: <time><?php echo $last_update[0];?>年<?php echo $last_update[1];?>月<?php echo $last_update[2];?>日</time>
        </div>
        <div class="titles-content">
          <table>
            <tr>
              <th class="t-gan"><span class="dw-title">作品名</span><span class="dw-gan"> / 総話数</span><span class="dw-lastupdate"> / 最終更新日 / </span><span class="dw-ncode">ncode</span></th>
            </tr>
            <?php for($i=0; $i<count($titles);$i++):?>
              <?php if($i % 2 ===0):?>
                <tr <?php echo "style='background-color:#f9f9f9'"?>>
              <?php else:?>
                <tr>
              <?php endif;?>
                <td class="title-gan">
                  <span class="dw-title"><?php echo $titles[$i]; ?></span>
                  <span class="dw-gan"> / <?php echo $gans[$i]; ?>話</span>
                  <?php
                  $lu_array = preg_split("/\s/", $lastups[$i]);
                  $last_up_temp = $lu_array[0];
                  $last_up = preg_split('/-/', $last_up_temp);
                  ?>
                  <span class="dw-lastupdate"> / <?php echo $last_up[0].'.'.$last_up[1].'.'.$last_up[2]; ?> / </span>
                  <span class="dw-ncode"><?php echo $ncodes[$i]; ?></span>
                </td>
            <?php endfor;?>
          </table>
        </div>
        <div class="dw-charts">
          <div class="dw-c-card sca">
            <div class="dw-c-card-title flexible">
              作品別総話数(x)、ユニークユーザ数(y)、獲得ポイント(r)
              <br><span class="dw-c-card-subtitle">rは円の半径で、実際の値の<?php echo $scale?>倍に変えています</span>
            </div>
            <canvas id="chart_sca" class="dw-canvas" width="100", height="100"></canvas>
          </div>
          <div class="dw-chart-wrapper">
            <div class="dw-c-card po">
              <div class="dw-c-card-title flexible">獲得ポイントの割合</div>
              <canvas id="chart_po" class="dw-canvas"></canvas>
            </div>
            <div class="dw-c-card un">
              <div class="dw-c-card-title flexible">ユニークユーザの割合</div>
              <canvas id="chart_un" class="dw-canvas"></canvas>
            </div>
            <div class="dw-c-card mk">
              <div class="dw-c-card-title flexible">作品別の指標1<br><span class="dw-c-card-subtitle">(ポイントは低いけど読者数は多い作品)</span></div>
              <canvas id="chart_mk" class="dw-canvas"></canvas>
            </div>
            <div class="dw-c-card ca">
              <div class="dw-c-card-title flexible">作品別の指標2<br><span class="dw-c-card-subtitle">(ポイントは低いけど読者数は多く、更新頻度が高い作品)</span></div>
              <canvas id="chart_ca" class="dw-canvas"></canvas>
            </div>
          </div>
        </div>
      </div>

    </section>
    <div class="to-detail-dw">
      <div class="to-detail-title-dw">作品詳細検索</div>
      <form action="detail.php" method="GET" class="to-detail-form-dw">
        <div class="to-detail-input-wrapper-dw">
          <input type="text" name="ncode" placeholder="ncode" class="to-detail-input-mini">
        </div>
        <div class="to-detail-btn-mini-space">
          <input type="submit" value='詳細表示' class="s-submit-btn-mini">
        </div>
      </form>
    </div>
  <footer class="bg-light">
    <div class="container p-3">
      <p class="text-center">Copyright(C) Search Narou Works.</p>
    </div>
  </footer>
  <!-- jQueryのライブラリー本体を読み込む -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
  <script src="../js/main.js"></script>
  <!-- chart.jsのcdn -->
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"
    integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg=="
    crossorigin="anonymous"></script>
  <!-- chart.jsのcdn(日付ﾃﾞｰﾀを扱う際に使用するプラグイン) -->
  <script
    src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

  <?php

  ?>
  <script>
    const ctx_sca = document.getElementById('chart_sca')
    const Chart_sca = new Chart(ctx_sca, {
      type: 'bubble',
      data: {
        label: '<?php echo $rec_rep['writer']?>の作品',
        datasets: [
          {
          <?php $i=0;?>
          <?php while($rec = $result_forChart -> fetch()):?>
            <?php if($i!==0):?>
            <?php echo '{'?>
            <?php endif;?>
            <?php
            $r= $rec['sum_po']*$scale;
            if(mb_strlen($rec['title'])>=8){
              $title_re_temp = mb_substr($rec['title'], 0, 8);
              $title_re = $title_re_temp.'…';
            }else{
              $title_re = $rec['title'];
            };
            ?>
            label: ["<?php echo $title_re;?>"],
            backgroundColor: "<?php echo $chart_colors[$i]?>",
            borderColor: "<?php echo $chart_colors[$i]?>",
            data:[{
              x: <?php echo $rec['general_all_no'];?>,
              y: <?php echo $rec['sum_un'];?>,
              r: <?php echo $r;?>
            }]
          }
          <?php if($i!==$count-1):?>
            <?php echo ','?>
          <?php endif;?>
          <?php $i++;?>
          <?php endwhile;?>
        ]
      },
      options: {
        responsive: true,
        scales:{
          y: {
            title:{
              display: true,
              text: "ユニークユーザ数",
              fontSize: 15,
            }
          },
          x: {
            title:{
              display: true,
              text: "総話数",
              fontSize: 15,
            }
          }
        },
        plugins:{
          legend:{
            display: true,
            position: "right",
            labels:{
              boxWidth: 20,
              color: '<?php echo $font_c;?>',
              usePointStyle: true,
            }
          }
        }
      }
    })
  </script>
<?php

//doughnut用(po)
$dou_labels_temp = [];
$dou_data_temp = [];
$dou_color_temp = [];
for($i=0; $i< count($titles); $i++){
  if(mb_strlen($titles[$i])>=8){
    $title_re_temp = mb_substr($titles[$i], 0, 8);
    $title_re = $title_re_temp.'…';
  }else{
    $title_re = $titles[$i];
  };
  $dou_labels_temp[] = '"'.$title_re.'"';
  $dou_data_temp[] = $pos[$i];
  $dou_color_temp[] = '"'.$chart_colors[$i].'"';
};
$dou_labels = implode(',', $dou_labels_temp);
$dou_data = implode(',', $dou_data_temp);
$dou_color = implode(',', $dou_color_temp);


?>
  <script>
    const ctx_po = document.getElementById('chart_po')
    const Chart_po = new Chart(ctx_po, {
      type: 'doughnut',
      data: {
        labels: [<?php echo $dou_labels?>],
        datasets: [{
          label: "<?php echo $rec_rep['writer']?>の作品",
          data: [<?php echo $dou_data?>],
          backgroundColor: [<?php echo $dou_color?>],
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        plugins:{
          legend:{
            display: true,
            position: "right",
            labels:{
              boxWidth: 20,
            },
          },
        }
      }
    })
  </script>
<?php

//doughnut用(un)
$un_labels_temp = [];
$un_data_temp = [];
$un_color_temp = [];
for($i=0; $i< count($titles); $i++){
  if(mb_strlen($titles[$i])>=8){
    $title_re_temp = mb_substr($titles[$i], 0, 8);
    $title_re = $title_re_temp.'…';
  }else{
    $title_re = $titles[$i];
  };
  $un_labels_temp[] = '"'.$title_re.'"';
  $un_data_temp[] = $uns[$i];
  $un_color_temp[] = '"'.$chart_colors[$i].'"';
};
$un_labels = implode(',', $un_labels_temp);
$un_data = implode(',', $un_data_temp);
$un_color = implode(',', $un_color_temp);

?>
  <script>
    const ctx_un = document.getElementById('chart_un')
    const Chart_un = new Chart(ctx_un, {
      type: 'doughnut',
      data: {
        labels: [<?php echo $un_labels?>],
        datasets: [{
          label: "<?php echo $rec_rep['writer']?>の作品",
          data: [<?php echo $un_data?>],
          backgroundColor: [<?php echo $un_color?>],
          hoverOffset: 4,
          borderAlign: 'inner',
        }]
      },
      options: {
        responsive: true,
        plugins:{
          legend:{
            display: true,
            position: "right",
            labels:{
              boxWidth: 20,
            },
          },
        }
      }
    })
  </script>
<?php
//bar用(mk)
$mk_labels_temp = [];
$mk_data_temp = [];
$mk_color_temp = [];
for($i=0; $i< count($titles); $i++){
  if(mb_strlen($titles[$i])>=8){
    $title_re_temp = mb_substr($titles[$i], 0, 8);
    $title_re = $title_re_temp.'…';
  }else{
    $title_re = $titles[$i];
  };
  $mk_labels_temp[] = '"'.$title_re.'"';
  $mk_data_temp[] = $mks[$i];
  $mk_color_temp[] = '"'.$chart_colors[$i].'"';
};
$mk_labels = implode(',', $mk_labels_temp);
$mk_data = implode(',', $mk_data_temp);
$mk_color = implode(',', $mk_color_temp);

?>
  <script>
    const ctx_mk = document.getElementById('chart_mk')
    const Chart_mk = new Chart(ctx_mk, {
      type: 'bar',
      data: {
        labels: [<?php echo $mk_labels?>],
        datasets: [{
          label: "<?php echo $rec_rep['writer']?>の作品",
          data: [<?php echo $mk_data?>],
          backgroundColor: [<?php echo $mk_color?>],
          hoverOffset: 4,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales:{
          y:{
            min:0
          }
        }
      }
    })
  </script>
<?php
//bar用(ca)
$ca_labels_temp = [];
$ca_data_temp = [];
$ca_color_temp = [];
for($i=0; $i< count($titles); $i++){
  if(mb_strlen($titles[$i])>=8){
    $title_re_temp = mb_substr($titles[$i], 0, 8);
    $title_re = $title_re_temp.'…';
  }else{
    $title_re = $titles[$i];
  };
  $ca_labels_temp[] = '"'.$title_re.'"';
  $ca_data_temp[] = $calcs[$i];
  $ca_color_temp[] = '"'.$chart_colors[$i].'"';
};
$ca_labels = implode(',', $ca_labels_temp);
$ca_data = implode(',', $ca_data_temp);
$ca_color = implode(',', $ca_color_temp);

?>
  <script>
    const ctx_ca = document.getElementById('chart_ca')
    const Chart_ca = new Chart(ctx_ca, {
      type: 'bar',
      data: {
        labels: [<?php echo $ca_labels?>],
        datasets: [{
          label: "<?php echo $rec_rep['writer']?>の作品",
          data: [<?php echo $ca_data?>],
          backgroundColor: [<?php echo $ca_color?>],
          hoverOffset: 4,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales:{
          y:{
            min:0
          }
        }
      }
    })
  </script>

</body>
</html>