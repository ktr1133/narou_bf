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
    <meta property="og:description" content="このサイトでは、政治家の実績を簡単に調べることができ、分かりやすくその結果を見ることができます。主に、地方議会(本会議)での発言(質問、提案等)によって実現した政策等について、わかりやすく表現することで訪問者の皆様に効率良く政治家の実績を情報提供できるよう心掛けています。" />
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

    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    <nav class="header">
      <div class="container clear">
        <div class="header-left">
          <h1><a class="header-title" href="#">Search Narou Works</a></h1>
        </div>
        <div class="header-right">
          <div class="header-items">
            <ul class="item-list">
              <li class="item"><a href="" class="list-item">Home</a></li>
              <li class="item"><a href="" class="list-item">Ranking</a></li>
              <li class="item"><a href="./about/index.html" class="list-item">About</a></li>
            </ul>
          </div>
          <div class="header-items-sp">
            <ul class="item-list">
              <li class="item"><a href="" class="list-item">Home</a></li>
              <li class="item"><a href="" class="list-item">Ranking</a></li>
              <li class="item"><a href="./about/index.html" class="list-item">About</a></li>
              <li class="item close"><a href="" class=""><span>Close</span></a></li>
            </ul>
          </div>
          <div id="sp-menue">
            <span></span>
          </div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </nav>

  <section class="top">
      <div class="container">
        <div class="top-wrap">
          <h1 class="top-title">
            なろう宝さがし
          </h1>
          <h2 class="top-subtitle">
            なろう作品のランキングに載らない良作を探そう
          </h2>
          <div class="top_visual"></div>
        </div>
      </div>
  </section>

  <section class="about">
      <div class="container clear">
          <h3 class="section-title">このサイトについて</h3>
          <div class="about-wrap">
              <div class="about-item">
                  <img src="./img/novel.jpg" alt="このサイトについて" class="about-img">
              </div>
              <div class="about-item">
                <div class="describe_wrap">
                  <div class="describe">
                    　このサイトでは、なろうapiを使用して取得したデータを蓄積し、独自のランキングを作成しています。<br><br>
                    　本サイトのランキングは、週間ユニークユーザ等の本家のランキングで使用していない値を重視して作成されています。<br><br>
                    　週間ユニークユーザは、同一端末からのアクセスを１日１回のみカウントとしたユニークアクセスの日曜日から土曜日分の合計値です。<br><br>
                    　この値は、本家のランキングで参照されているポイントとは関係なく、一定数の読者数がいることを示す値です。<br><br>
                    　こうした値を使用することで、ポイントは獲得していないけど、一定数の読者はいる作品を抽出しています。
                  </div>
                </div>
              </div>
          </div>
      </div>
  </section>


  <section class="search">
      <div class="container clear">
        <div class="search_wrapper">
          <h3 class="section-title">検索欄</h3>
          <form action="search.php" class="search_content">
            <dl>
              <div class="form-row">
                <dt class="form-item"><label for="time-span">期間</label></dt>
                <dd class="form-explain">
                  <div class="select-wrapper">
                    <select id="time-span" class="time-span">
                      <option value="選択してください" selected class="default">選択してください</option>
                      <option value="weekly" class="weekly">週間</option>
                      <option value="monthly" class="monthly">月間</option>
                      <option value="quarter" class="quarter">四半期</option>
                      <option value="half" class="half">半期</option>
                      <option value="yearly" class="yearly">年間</option>
                    </select>
                  </div>
                </dd>
              </div>
              <div class="form-row">
                <dt class="form-item"><label for="unique">週間ユニークユーザ数</label></dt>
                <dd class="form-explain">
                  <div class="range">
                    <input type="text" id="unique_from" name="unique_num[unique_from]" class="unique-text" placeholder="0">
                    <span> ～ </span>
                    <input type="text" id="unique_to" name="unique_num[unique_to]" class="unique-text" placeholder="入力してください">
                  </div>
                </dd>
              </div>
              <div class="form-row">
                <dt class="form-item"><label for="point">週間獲得ポイント</label></dt>
                <dd class="form-explain">
                  <div class="range">
                    <input type="text" id="point_from" name="point_num[point_from]" class="point-text" placeholder="0">
                    <span> ～ </span>
                    <input type="text" id="point_to" name="point_num[point_to]" class="point-text" placeholder="入力してください">
                  </div>
                </dd>
              </div>
              <div class="form-row">
                <dt class="form-item"><label for="general_all_no">総話数</label></dt>
                <dd class="form-explain">
                  <div class="range">
                    <input type="text" id="general_all_no_from" name="gan_num[gan_from]" class="gan-text" placeholder="0">
                    <span> ～ </span>
                    <input type="text" id="general_all_no_to" name="gan_num[gan_to]" class="gan-text" placeholder="入力してください">
                  </div>
                </dd>
              </div>
              <div class="form-row ver_radio">
                <dt class="form-item ver_radio">
                    <label for="radio">平均更新頻度</label>
                </dt>
                <dd class="form-explain select">
                  <div class="select-wrapper">
                    <select id="frequency" class="frequency">
                      <option value="" selected class="default">選択してください</option>
                      <option value="lessPerM" class="lessPerM">1(回/月)未満</option>
                      <option value="morePerM_lessPerW" class="morePerM_lessPerW">1(回/月)以上1(回/週)未満</option>
                      <option value="morePerW_lessPerD" class="morePerW_lessPerD">1(回/週)以上1(回/日)未満</option>
                      <option value="morePerD" class="morePerD">1(回/日)以上</option>
                    </select>
                  </div>
                </dd>
              </div>
            </dl>
            <div class="submit-btn-space">
              <input type="submit" value="検索" class="submit-btn">
            </div>
          </form>
        </div>
      </div>
  </section>

  <section class="contact">
    <div class="container clear">
      <h3 class="section-title">お問い合わせ</h3>
      <p class="text-center">「メール」または「Twitter」よりお気軽にご相談ください！</p>
      <div class="d-flex justify-content-center">
          <div class="h-100 p-3 text-center">
              <a href="" class="contact_img">
                  <img src="./img/mail.svg" class="card-img-top p-5 w-50 h-50" alt="PAA">
              </a>
              <h4 class="card-title fw-bold">e-mail</h4>
              <a class="contact_name text-center fs-6" href target="_blank">politicalactivityanalysis@gmail.com</a>
          </div>
          <div class="h-100 p-3 text-center">
              <a href="" class="contact_img">
                  <img src="./img/twitter.svg" class="card-img-top p-5 w-50 h-50" alt="seisakurei2">
              </a>
              <h4 class="card-title fw-bold text-center">twitter</h4>
              <a class="contact_name" href target="_blank">@example</a>
          </div>
      </div>
  </div>
  </section>

  <footer class="bg-light">
      <div class="container p-3">
          <p class="text-center">Copyright(C) Political Activity Analysis.</p>
      </div>
  </footer>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="./js/main.js"></script>
  </body>
</html>