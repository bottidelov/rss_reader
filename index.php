<?php
require_once 'php/rss_reader_function.php';

/**************************************************************/
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="format-detection" content="telephone=no"><!--検出機能の設定・電話番号自動検出のオフ-->
<meta name="viewport" content="width=device-width,minimum-scale=1">
<!--可視範囲の指定、device-widthはデバイスの横幅で設定、scaleは倍率を表す-->

<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/common.css">

<!-- webフォント -->
<link href='https://fonts.googleapis.com/css?family=Candal|Pacifico' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./assets/js/jquery.min.js"></script>

<title>RSSりーだー</title>
</head>
<body>

	<div class="wrap p-10 clear-fix boxShadow bg0">
    	<h2 class="textShadow"><span>RSS Reader ＆ XML output</span></h2>

    	<!-- リンク選択空間 -->
        <div class="text-center textBox  p-10 m-bottom1 bg1">
        	<h3>RSSライブラリ</h3>

        	<?php foreach($rss_links as $rss_link):?>
            	<div class ="btn-group">
            	<span class="btn btn-primary boxShadow rss-btn">
                <a href="<?php echo $rss_link["link"];?>"><?php echo $rss_link["name"];?>
                </a>
                </span>
                <button class="btn btn-info del-btn" value="<?php echo $rss_link["id"];?>" >
                x
	            </button>
                </div>
            <?php endforeach;?>

            <!-- ページャー -->
            <?php require_once './php/pager.php';?>

			<hr>

			<!-- RSS生成 -->
            <h3>取得先URL</h3>
            <input type="text" class="input-t" id="url" placeholder="アドレスを入力"></input>
            <button class="getbtn btn btn-success circle-btn textShadow">★</button>

            <hr>

            <!-- RSSリンクボタン追加 -->
            <h3 class="open_add_rss">RSSリンク追加</h3>
            <div class="add_rss_form">
            <form action="" method="post">
            	<p>
            	<input type="text" name="add_rss_name" placeholder="サイト名を入力">
            	<input type="text" name="add_rss_link" placeholder="リンクを入力">
            	</p>
            	<input type="submit" value="＋" name="add_rss_done"
            		   class="btn btn-danger circle-btn textShadow">
            	<p class="error"><?php if(isset($_POST["message"])){echo $_POST["message"];};?></p>
            </form>
            </div>

        </div>

        <!-- 本文表示空間 -->
        <div class="m-bottom20 p-10">
        	<span class="loading"></span>
            <dl class="ajaxSpace cf"></dl>
        </div>

        <!-- xml表示空間 -->
        <div class="">
        	<h3>XMLデータ出力</h3>
            <textarea class="allDate bg1" placeholder="ここにXMLを表示"></textarea>
        </div>

        <button id="page-top" class="btn btn-warning circle-btn textShadow">↑</button>

	</div>

<script>
$(document).ready(function(){

	 //ページトップ
    $(function() {
        var topBtn = $('#page-top');
        topBtn.hide();
        //スクロールが特定のheightに達したらボタン表示
        $(window).scroll(function () {
            if ($(this).scrollTop() > 500) {
                topBtn.fadeIn();
            } else {
                topBtn.fadeOut();
            }
        });
      topBtn.click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 500);
            return false;
        });
    });

    //対象要素を非表示に設定
    $(".add_rss_form").css("display", "none");
    $(".open_add_rss").css("cursor","pointer").click(function()
    {
        if ($('.add_rss_form').css('display') == 'none')
        {
	    	//瞬間表示の場合toggle();を用いる
	        $('.add_rss_form').slideDown('fast');
	    }
	    else
		{
	    	$('.add_rss_form').slideUp('fast');
	    }
    });

	//RSSライブラリボタンをクリック
	$(".btn-group .rss-btn").css("cursor","pointer").click(function(){
		//ボタンのhrefの値を取得
		var buttonURL = $("a",this).attr("href");
		//input欄にhrefの値を挿入
		$("#url").val(buttonURL);
		//aタグの無効化
        return false;
    });

    //RSS削除ボタンをクリック
    $(".btn-group .del-btn").css("cursor","pointer").click(function()
   		{
   		//ボタンのvalue値を取得
		var buttonURL = $(this).val();

		if(!confirm('このボタンを削除しますか？'))
			{
	        //キャンセルの時の処理
	        return false;
	    }
	    else
		{
	        //OKの処理、jquery・$.post関数によりphpにIDを送信
	      /*  $.post(
	            "./php/delete_db.php", {input1:buttonURL,}, //json.phpへpost形式で値を渡す。
	            //通信成功時
	            function(json){alert("対象のボタンを削除しました");
	            //リロード
	            location.reload();
		        return false;
	         });*/

	         //$.ajax関数にてデータを送信
	         var url = "./php/delete_db.php";
	         var value = {input1:buttonURL};

	         $.ajax({
	        	 type: "POST",
	        	 url:url,
	        	 data:value,
	     	    })
	     	    .done(function(data)
	     	    	{
	     	  		  	alert("対象のボタンを削除しました");
	    	            location.reload();
	    		        return false;
	     	    	});

	    }
    });



    //RSS記事作成ボタンをクリック
    $('.getbtn').click(function(){
        var url = encodeURIComponent($('#url').val());
        $(".loading").html("<img src='assets/img/load.gif'/>");
        $('.allDate').val('通信中...');
        $(".ajaxSpace").empty();

        $.ajax({
            async: true,
            cache: false,
            type: "GET",
            //ajax.phpに処理を送信、判定が正ならurlのデータを変数に格納しdataに出力
            url: "php/ajax.php?url="+url,
            dataType: "xml",
            timeout:10000,
            //成功時、以下の処理
            success: function(data)
            {
               console.log(data);
                $("item",data).each(function(){
                    $(".ajaxSpace").append("<div class='clearfix m-w100 m-bottom10 bg1 p-10 under-line underShadow'><p class='bold m-bottom5'>"+
                            "<a href='"+$("link",this).text()+"'>"
                            +$("title",this).text()+"</a></p><dd'>"
                            +$("description",this).text()+"<br/>更新日："+$("pubDate",this).text()+"</dd></div>"
                            );

                    $.ajax({
                        type: "GET",
                        url: "php/ajax.php?url="+url,
                        dataType: "text",
                        success: function(data){
                            $('.allDate').val(data);
                        },
                    });

                });

            },
            error:function() {
            	alert('問題が発生しました');
            	$(".loading").empty();
            },
            complete:function(data) {
                $(".loading").empty();
                //$(".loading").html("<p>通信終了</p>");
           },
        });
    });

});
</script>

</body>