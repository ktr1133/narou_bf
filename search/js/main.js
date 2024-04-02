/*--- loading画面設定 ---*/
// クラス変更
window.onload = function () {
    const spinner = document.getElementById('loading');
    spinner.classList.add('loaded');
}

//safari対策
window.onpageshow = function (event) {
    if (event.persisted) {
        window.location.reload();
    }
};

/*-- スマホナビの表示・非表示 ---*/
$(function() {
    const close = $('#sp-menu, .close')
    const nav = $('.header-items-sp')
    close.on('click', function(){
        nav.toggleClass('toggle');
    });
});

/*-- 画面ﾄｯﾌﾟに戻るﾎﾞﾀﾝの設置-- */
$(function () {
    $("#js-pagetop").click(function () {
        $('html, body').animate({
        scrollTop: 0
        }, 500);
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > 80) {
        $('#js-pagetop').fadeIn(300).css('display', 'flex')
        } else {
        $('#js-pagetop').fadeOut(300)
        }
    });
});

/*----- ﾗﾝｷﾝｸﾞ選択状況 ----- */
jQuery('#r-time-span').on('click', function(){

})