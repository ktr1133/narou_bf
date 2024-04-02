<?php
require_once(dirname(__FILE__).'/../database.php');
require_once(dirname(__FILE__).'/../classes/detail_sql.php');

$n = "'".$_GET['ncode']."'";
$ncode = $_GET['ncode'];

try{
  // 接続
  $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
} catch(PDOException $e){
    echo "データベース接続失敗" . PHP_EOL."<br>";
  echo $e->getMessage();
  exit;
};

$detail = new detailSql($n);

$master_rec = $detail -> get_master();

$time_spans = $detail -> time_span();

$point_a = $detail -> get_point('all');
$sum_p = $detail -> get_sum_p();

$unique_a = $detail -> get_unique('all');
$sum_u = $detail -> get_sum_u();
$mark_a = $detail -> get_mark('all');
$mean_mk = $detail -> get_mean_mk();
$calc_a = $detail -> get_calc('all');
$mean_c = $detail -> get_mean_c();


$time_spans = $detail -> time_span();
$time_temp = $time_spans['weekly'];
$time = make_for_column($time_temp);
$rank_un_w = $detail -> calc_rank_un('weekly');
$rank_un_m = $detail -> calc_rank_un('monthly');
$rank_un_h = $detail -> calc_rank_un('half');
$rank_un_a = $detail -> calc_rank_un('all');

$rank_po_w = $detail -> calc_rank_po('weekly');
$rank_po_m = $detail -> calc_rank_po('monthly');
$rank_po_h = $detail -> calc_rank_po('half');
$rank_po_a = $detail -> calc_rank_po('all');

$rank_mk_w = $detail -> calc_rank_mk('weekly');
$rank_mk_m = $detail -> calc_rank_mk('monthly');
$rank_mk_h = $detail -> calc_rank_mk('half');
$rank_mk_a = $detail -> calc_rank_mk('all');

$rank_c_w = $detail -> calc_rank_c('weekly');
$rank_c_m = $detail -> calc_rank_c('monthly');
$rank_c_h = $detail -> calc_rank_c('half');
$rank_c_a = $detail -> calc_rank_c('all');

$update_temp01 = $time_temp[0];
$update_temp02 = explode(' ', $update_temp01);
$update_temp03 = explode('-', $update_temp02[0]);
$last_update = $update_temp03[0].'年'.$update_temp03[1].'月'.$update_temp03[2].'日';

$perY = 1 / 365;
$perH = 1 / 183;
$perM = 1 / 30;
$perW = 1 / 7;
$perD = 1;
if($master_rec['mean_uf'] < $perY){
  $update_fre = '１年に１回未満';
}else if($master_rec['mean_uf'] < $perH){
  $update_fre = '１年に１回以上半期に１回未満';
}else if($master_rec['mean_uf'] < $perM){
  $update_fre = '半期に１回以上月に１回未満';
}else if($master_rec['mean_uf'] < $perW){
  $update_fre = '月に１回以上週に１回未満';
}else if($master_rec['mean_uf'] < $perD){
  $update_fre = '週に１回以上日に１回未満';
}else{
  $update_fre = '日に１回以上';
}

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
      <li><a href="https://freenovelanalysis.com/search/result.php">Create Ranking By Works</a></li>
      <li>Detail Works</li>
    </div>
  </ol>

  <section class="detail">
    <div class="container clear">
      <div class="detail-title">
        <?php echo $master_rec['title']?>
      </div>
      <div class="update">
        Last Up Date: <time><?php echo $last_update;?></time>
      </div>
      <dl>
        <div class="d-row">
          <dt>作者名</dt>
          <dd><?php echo $master_rec['writer']?></dd>
        </div>
        <div class="d-row">
          <dt>総話数</dt>
          <dd><?php echo $master_rec['general_all_no']?></dd>
        </div>
        <div class="d-row">
          <dt>平均更新頻度</dt>
          <dd><?php echo $update_fre;?></dd>
        </div>
      </dl>
      <div class="charts-wrapper">
        <div class="c-card">
          <div class="c-card-title flexible">指標1(ポイントの割に読者数は多い作品)</div>
          <canvas id="chart_mk" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd><?php echo $rank_mk_w?><span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd><?php echo $rank_mk_m?><span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>半期</dt><dd><?php echo $rank_mk_h?><span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd><?php echo $rank_mk_a?><span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title flexible">指標2(ポイントの割に読者数は多く、更新頻度が高い作品)</div>
          <canvas id="chart_calc" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd><?php echo $rank_c_w?><span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd><?php echo $rank_c_m?><span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>半期</dt><dd><?php echo $rank_c_h?><span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd><?php echo $rank_c_a?><span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title flexible">指標3(ポイントが高い作品)</div>
          <canvas id="chart_po" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd><?php echo $rank_po_w?><span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd><?php echo $rank_po_m?><span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>半期</dt><dd><?php echo $rank_po_h?><span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd><?php echo $rank_po_a?><span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title">指標4(ユニークユーザ数が多い作品)</div>
          <canvas id="chart_un" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd><?php echo $rank_un_w?><span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd><?php echo $rank_un_m?><span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>半期</dt><dd><?php echo $rank_un_h?><span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd><?php echo $rank_un_a?><span>位</span></dd></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php require_once('../PHPparts/footer.php')?>

  <!-- jQueryのライブラリー本体を読み込む -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
  <script src="../js/main.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"
    integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg=="
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

  <?php
  $time_spans_for_g_temp = [];
  $point_for_g_temp = [];
  $unique_for_g_temp = [];
  $mark_for_g_temp = [];
  $calc_for_g_temp = [];
  $time_spans = $detail -> time_span();
  $time_spans_all = $time_spans['all'];

  foreach ($time_spans_all as $time){
    $t_temp = explode(' ', $time);
    $t = $t_temp[0];
    $time_spans_for_g_temp[] = "'".$t."'";
    $p = $point_a[$time];
    $point_for_g_temp[] = $p;
    $un = $unique_a[$time];
    $unique_for_g_temp[] = $un;
    $mk = $mark_a[$time];
    $mark_for_g_temp[] = $mk;
    $ca = $calc_a[$time];
    $calc_for_g_temp[] = $ca;
  }
  krsort($time_spans_for_g_temp);
  $time_spans_for_g = implode(', ', $time_spans_for_g_temp);
  krsort($point_for_g_temp);
  $point_for_g = implode(', ', $point_for_g_temp);
  krsort($unique_for_g_temp);
  $unique_for_g = implode(', ', $unique_for_g_temp);
  krsort($mark_for_g_temp);
  $mark_for_g = implode(', ', $mark_for_g_temp);
  krsort($calc_for_g_temp);
  $calc_for_g = implode(', ', $calc_for_g_temp);

  ?>
  <script>
    var ctx = document.getElementById('chart_mk');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [<?php echo $time_spans_for_g?>],
        datasets: [{
          label: '指標1の値',
          data: [<?php echo $mark_for_g?>],
          borderColor: '#5eccb0ff',
        }]
      }
    })

    var ctx = document.getElementById('chart_calc');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [<?php echo $time_spans_for_g?>],
        datasets: [{
          label: '指標2の値',
          data: [<?php echo $calc_for_g?>],
          borderColor: '#bed630',
        }]
      }
    })

    var ctx = document.getElementById('chart_po');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [<?php echo $time_spans_for_g?>],
        datasets: [{
          label: '週間獲得ポイント数',
          data: [<?php echo $point_for_g?>],
          borderColor: '#f25814',
        }]
      }
    })

    var ctx = document.getElementById('chart_un');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [<?php echo $time_spans_for_g?>],
        datasets: [{
          label: '週間ユニークユーザ数',
          data: [<?php echo $unique_for_g?>],
          borderColor: '#6fa8dc',
        }]
      }
    })
  </script>
</body>
</html>