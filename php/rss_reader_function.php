<?php
//require_once 'uF.php';
//プログラム側でクラス使用の為に他ファイルを呼び出す際使用
require_once 'autoload.php';
try{

	//ユーザー定義クラスのインスタンス
	$userFunc = new /*My\*/uF();

	// DB接続(シリアライズ化はできない)
	$pdo = $userFunc->getPDO();

/*OPPテスト用関数郡*******************************/

	//var_dump($userFunc);
	echo $userFunc->a;
	//echo $userFunc->aa;
	echo uF::deff;
	echo $userFunc->getDeff();

	$userFunc->test();

	$arg = "もっすもっす";
	$userFunc->setDeff($arg);
	echo $userFunc->aaaa;

	$core_userFunc = new core_uF();
	echo $core_userFunc->a;
	$core_userFunc->test();

	$userFunc->hogehoge = "これがマジックメソッドちゃんですか";
	echo $userFunc->hogehoge;

	//$userFunc->showNamespace;

	//例外のスロー
	/*
	if($arg == "もっすもっす"){
		throw new PDOException("mosgreeeen!!!");
	}*/

/***********************************************/

	//memcacheテスト ※事前にmemcacheサーバーを開く必要あり
	/*
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ("接続できませんでした");
	$version = $memcache->getVersion();
	echo "サーバのバージョン: ".$version."<br/>\n";
	$memcache->set('var_key', 'memcache機能してますよ！※3秒後に消失', MEMCACHE_COMPRESSED, 3);
	echo $memcache->get('var_key');
	sleep(3);
	echo $memcache->get('var_key');
	$memcache->close();
	*/

	//memcacheによりpdoの参照回数を減らす
	/*$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ("接続できませんでした");

	$m_key = "m_key";
	$m_key2 = "m_key2";
	$m_key3 = "m_key3";
	$numPages = $memcache ->get($m_key3);
	$page = $memcache ->get($m_key2);
	$rss_links = $memcache ->get($m_key);

	if (empty($rss_links))
	{
		// キャッシュにセット(10分)
		$memcache->set($m_key3, $numPages, MEMCACHE_COMPRESSED, 600);//numpages
		$memcache->set($m_key, $rss_links, MEMCACHE_COMPRESSED, 600);//pages
		$memcache->set($m_key2, $page, MEMCACHE_COMPRESSED, 600);//links
		//echo "メモキャッシュに初回格納！";

		//ページャーは$numPages変数が固定されてしまうのでmemcacheでは処理できない！
	}

	//memcacheの全項目をクリア
	//$memcache->flush();
	// memcache切断
	$memcache->close();
	*/


	/*記事およびページャー記述*/
	#投稿数の取得
	$sql = "SELECT COUNT(*) AS hits FROM trn_rss_libraries WHERE user_id = 0";
	$stmt = $pdo ->query($sql);
	$res = $stmt ->fetch();
	$hits = $res["hits"];

	$numPages = ceil($hits / 10);#ページ数の設定、ceilで小数点以下切り上げr
	if(isset($_GET["p"])){ #ページリンク押した場合GET値取得、偽の場合1
		$page = $_GET["p"];
	}else{
		$page = 1;
	}

	//記事表示に用いるLIMIT文含む変数を取得
	$offset = ($page - 1)*10; #1ページ目の場合0からSELECT文末尾の値までを取得、
	#2ページ目の場合(1*表示数 == 今までの取得数)から同じく取得

	//表示記事取得(表示制限及びトランザクションロック)
	$stmt = $pdo ->query("SELECT * FROM trn_rss_libraries
						  ORDER BY id
						  DESC
						  LIMIT {$offset},10
						  FOR UPDATE
						  ");
	$rss_links = $stmt ->fetchAll();

	//ロック･･･select文にロックを設定し、配下のSQL発行時にて他者の操作を制限する。
	//主にinsert,update,deleteの動作の際に用いられる
	//排他ロック･･･コミット若しくはロールバックまで他者はページ読み込みも変更もできない･･･FOR UPDATE
	//共有ロック･･･(同上)まで他者はページ読み込みは可能だが変更はできない･･･LOCK IN SHARE MODE

	/*記事およびページャー記述 おわり*/

	//POST送信
	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		if(isset($_POST["add_rss_done"]))
		{
			//フォーム値
			$add_rss_name = $_POST["add_rss_name"];
			$add_rss_link = $_POST["add_rss_link"];

			//警告メッセージ
			$message1 = "※空白を埋めてください";
			$message2 = "※規定の長さを越えています";

			//バリデーション実行、戻り値のvar_flagを変数に格納
			$flag = $userFunc->valid($message1,$message2,$add_rss_name,$add_rss_link);

			//バリデーション成功時、ボタン追加
			if($flag == TRUE)
			{
				//トランザクション
				try {
					$stmt = $pdo ->prepare("INSERT INTO trn_rss_libraries(user_id,name,link)
										    VALUES(?,?,?)");
					$stmt ->execute(array("",$add_rss_name,$add_rss_link));
					//ロックのチェック(コミットまでの時間を延長)
					//sleep(5);
					$pdo ->commit();
					header('Location: index.php');
					exit;
				}catch(Exception $e)
				{
					//ロールバック
					$pdo ->rollback();
					echo $e->getMessage();
				}
			}
		}
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit;
}