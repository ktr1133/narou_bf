
<?php
//require_once(dirname(__FILE__).'/../database.php');
require_once('D:\MAMP\htdocs\Novelserver\database.php');
require_once(dirname(__FILE__).'/../functions/make_for_column.php');
require_once(dirname(__FILE__).'/../functions/make_time_span.php');
require_once(dirname(__FILE__).'/../functions/create_sql.php');
require_once(dirname(__FILE__).'/../functions/writerRankSql.php');
require_once(dirname(__FILE__).'/../classes/detail_sql.php');
require_once(dirname(__FILE__).'/../classes/prepare_sql.php');
require_once(dirname(__FILE__).'/../classes/writer_detail_sql.php');

echo '<br>';
echo dirname(__FILE__).'<br>';


?>

<div class="dw-chart-container mk">
  <canvas id="chart_mk" class="dw-canvas"></canvas>
</div>

<div class="dw-chart-container mk">
  <canvas id="chart_mk2" class="dw-canvas"></canvas>
</div>

<!-- jQueryのライブラリー本体を読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
<script src="./js/main.js"></script>
<!-- chart.jsのcdn -->
<script
  src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"
  integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg=="
  crossorigin="anonymous"></script>
<!-- chart.jsのcdn(日付ﾃﾞｰﾀを扱う際に使用するプラグイン) -->
<script
  src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>


<script>
  const ctx_mk = document.getElementById('chart_mk')

  var data = {
    labels: ["おっさんが始める異世界雑記ブログ","ヘルモード　～やり込み好きのゲーマーは廃設定の異世界で無双する～"],
    datasets: [{
      label: "ハム男の作品",
      data: [0.01369,0.00909],
      backgroundColor: ["rgba(94, 204, 176, 0.5)","rgba(204, 93, 188, 0.5)"],
      hoverOffset: 4,
      borderWidth: 1
    }]
  };
  var options = {
    responsive: true,
    scales:{
      y:{
        min:0
      }
    }
  }

  const Chart_mk = new Chart(ctx_mk, {
    type: 'bar',
    data: data,
    options: options
  })

</script>

<script>

  const ctx_mk2 = document.getElementById('chart_mk2')

  var data2 = {
    labels: ["おっさんが始める異世界雑記ブログ","ヘルモード　～やり込み好きのゲーマーは廃設定の異世界で無双する～"],
    datasets: [{
      label: "ハム男の作品",
      data: [0.01469,0.00609],
      backgroundColor: ["rgba(94, 204, 176, 0.5)","rgba(204, 93, 188, 0.5)"],

      borderWidth: 1
    }]
  }

  var options2 = {
    responsive: true,
    scales:{
      y:{
        min:0
      }
    }
  }

  const Chart_mk2 = new Chart(ctx_mk2, {
    type: 'bar',
    data: data2,
    options: options2
  })


</script>



