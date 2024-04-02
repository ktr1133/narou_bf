<?php
try{
	// 接続
	$db = new PDO('mysql:host=localhost;dbname=narou', 'root', 'root');
} catch(PDOException $e){
    echo "データベース接続失敗" . PHP_EOL."<br>";
	echo $e->getMessage();
	exit;
};
require_once('./functions/point_of_time.php');
$years = pick_up_year();

?>
<!doctype html>
<html lang="jp">
  <head>
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
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="novel_icon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">

    <title>Search Narou Works</title>
  </head>
  <body>
  <?php require_once(dirname(__FILE__).'/classes/../PHPparts/nav.php')?>
  <section class="archive">
      <div class="container clear">
        <h3 class="section-title">アーカイブ</h3>
        <div class="a-text">
          &emsp;過去のランキングを閲覧することができます。
        </div>
        <form action="index.php" method="get" class="archive_content">
          <dl>
            <div class="a-form-row">
              <dt class="a-form-item"><label>時点</label></dt>
              <dd class="a-form-explain">
                <div class="a-select-wrapper point-of-time">
                  <form id="p-y-form" action="test.php" method="get" class="p-y-form">
                    <select name="p-year" id="p-year" class="point-of-time">
                      <option value="default" selected>選択してください</option>
                      <?php for($i=0; $i<count($years); $i++):?>
                      <option value="<?php echo $years[$i]?>"><?php echo $years[$i]?></option>
                      <?php endfor;?>
                    </select>
                  </form>
                  <form id="p-m-form" action="test.php" method="get" class="p-m-form">
                  <?php
                    $y = $_GET['p-year'];
                    echo '$get: ';
                    var_dump($_GET);
                    echo '<br>';
                    $months = pick_up_month($y);
                    echo '<br>$months: ';
                    var_dump($months);
                    echo '<br>';
                  ?>
                    <select name="p-month" id="p-month" class="point-of-time">
                      <option value="default" selected>選択してください</option>
                      <?php for($i=0; $i<count($months); $i++):?>
                      <option value="<?php echo $months[$i]?>"><?php echo $months[$i]?></option>
                      <?php endfor;?>
                    </select>
                  </form>
                  <form id="p-d-form" action="index.php" method="get" class="p-d-form">
                    <?php
                      $m = $_GET['p-month'];
                      echo '$m: ';
                      var_dump($m);
                      echo '<br>';
                      $months = pick_up_month($y);
                      $days = pick_up_day($y, $m[1]);
                      echo '<br>$days: ';
                      var_dump($days);
                      echo '<br>';
                    ?>
                    <select name="p-day" id="p-day" class="point-of-time">
                      <option value="default" selected>選択してください</option>
                      <?php for($i=0; $i<count($days); $i++):?>
                      <option value="<?php echo $months[$i]?>"><?php echo $months[$i]?></option>
                      <?php endfor;?>
                    </select>
                  </form>

                </div>
              </dd>
            </div>
            <div class="a-form-row">
              <dt class="a-form-item"><label for="a-time-span">期間</label></dt>
              <dd class="a-form-explain">
                <div class="a-select-wrapper">
                  <select name="a_time_span" id="a-time-span" class="time-span">
                    <option value="weekly" selected>週間</option>
                    <option value="monthly">月間</option>
                    <option value="half">半期</option>
                    <option value="yearly">年間</option>
                    <option value="all">累計</option>
                  </select>
                </div>
              </dd>
            </div>
            <div class="a-form-row">
              <dt class="a-form-item"><label for="a-cate">指標</label></dt>
              <dd class="a-form-explain a_cate">
                <div class="a-select-wrapper">
                  <select name="a_cate" id="a-cate">
                    <option value="lowPHighU" selected>ポイントの割に読者数は多い作品</option>
                    <option value="lowPHighUHighF">ポイントの割に読者数は多く、更新頻度が高い作品</option>
                  </select>
                </div>
              </dd>
            </div>
          </dl>
          <div class="a-btn-space">
            <input type="submit" name="submit" value="選択" class="r-form-submit">
          </div>
        </form>
      </div>
    </section>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="./js/main.js"></script>
  </body>

  <?php
  require_once('./classes/prepare_sql.php');
  require_once('./functions/create_sql.php');
  require_once('./functions/make_time_span.php');
  try{
    // 接続
    $db = new PDO('mysql:host=localhost;dbname=narou', 'root', 'root');
  } catch(PDOException $e){
      echo "データベース接続失敗" . PHP_EOL."<br>";
    echo $e->getMessage();
    exit;
  };
  $c = $_GET['cate'];
  $t = $_GET['time_span'];
  $gan = $_GET['gan_num'];
  $uf = $_GET['frequency'];
  $paramsClass = new PrepareSQL($c, $t, $gan, $uf);


  $sql = createSQL($c, $t, $gan, $uf);
 
  $result = $db -> query($sql);
  ?>
  
  <?php $i=1?>
  <?php $before_ave= -999999?>
  <?php while($record = $result -> fetch()):?>
  <?php if($i % 2 ===1):?>
      <tr style='background-color:#f9f9f9'>
  <?php else:?>
      <tr>
  <?php endif;?>
      <?php if($record['ave'] == $before_ave):?>
          <th><?php echo $i -1;?>位</th>
      <?php else:?>
          <th><?php echo $i;?>位</th>
      <?php endif;?>
      <td class="title-writer">
          <span class="r-title"><?php echo $record['title']; ?></span>
          <span class="r-writer"> / <?php echo $record['writer']; ?></span>
          <span class="r-writer"> / <?php echo $record['ave']; ?></span>
          <span class="ncode"> / <?php echo $record['ncode']; ?></span>
          <?php $before_ave = $record['ave']?>
      </td>
      <br>
      <?php $i++?>
  <?php endwhile;?>
  
  <form action="./detail/detail.php" method="GET">
      <label for="ncode">ncode</label>
      <input type="text" name="ncode" placeholder="ncode">
      <input type="submit" value='詳細表示'>
  </form>
  
  <!-- jQueryのライブラリー本体を読み込む -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
  <script src="./js/main.js"></script>

</html>

