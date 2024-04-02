<?php
//ｽﾗｲﾄﾞﾊﾞｰ設定
try{
	// 接続
	$db = new PDO('mysql:host=localhost;dbname=narou', 'root', 'root');
} catch(PDOException $e){
    echo "データベース接続失敗" . PHP_EOL."<br>";
	echo $e->getMessage();
	exit;
};
require_once('../functions/point_of_time.php');
$years = pick_up_year();
var_dump($years);

?>
<!doctype html>
<html lang="jp">
    <head>
        <!-- noUiSlider CSS & CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
        <link rel="stylesheet" href="./css/slide_bar.css">
    </head>
    <body>
    
        <div class="a-form-row pot">
            <dt class="a-form-item"><label for="point-of-time">時点</label></dt>
            <dd class="a-form-explain">
                <div class="a-slider-wrapper">
                    <div id="slider"></div>
                    <div class="slider-span"><span id="range_s"></span><span id="range_to"> ～ </span><span id="range_e"></span></div>
                </div>
            </dd>
        </div>
    
    
        <script>
            tooltipsFommatter = {
            to: (value) => {
                let date = new Date(+value);
                return date.getFullYear() + '/' + (1+date.getMonth()).toString() + '/' + date.getDate();
            },
            from: (value) => {
                let date = new Date(+value);
                return date.getFullYear() + '/' + (1+date.getMonth()).toString() + '/' + date.getDate();
            },
            }
    
            window.addEventListener('DOMContentLoaded', (event) => {
            let range_s = new Date('2023/5/1');
            let range_e = new Date('2023/6/30');
    
            document.getElementById('range_s').innerHTML = range_s.getFullYear() + '/' + (1+range_s.getMonth()).toString() + '/' + range_s.getDate();
            document.getElementById('range_e').innerHTML = range_e.getFullYear() + '/' + (1+range_e.getMonth()).toString() + '/' + range_e.getDate();
    
            let slider = document.getElementById('slider');
    
            noUiSlider.create(slider,{
                start: [range_s.getTime(), range_e.getTime()],
                behaviour: 'drag',
                connect: true,
                step: 24 * 60 * 60 * 1000,
                orientation: 'horizontal', // 'horizontal' or 'vertical'
                tooltips: [tooltipsFommatter, tooltipsFommatter],
                range: {
                'min': new Date('2023/1/23').getTime(),
                'max': new Date('2023/8/1').getTime()
                },
            });
    
    
            let range_ids = [
                document.getElementById('range_s'),
                document.getElementById('range_e')
            ]
            slider.noUiSlider.on('update', (values, handle) => {
                let date = new Date(+values[handle]);
                range_ids[handle].innerHTML = date.getFullYear() + '/' + (1+date.getMonth()).toString() + '/' + date.getDate();
                slider2.noUiSlider.setHandle(handle, values[handle]);
            });
            });
        </script>
    </body>
</html>