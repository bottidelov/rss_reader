 <?php

//DB接続関数の取得
 require_once 'autoload.php';

// POSTされたパラメータを受け取る
$buttonURL = $_POST["input1"];

//DB接続インスタンス生成
$pdo = uF::getPDO();

	//トランザクション処理を開始(DB側のオートコミットをオフにする)
	//$pdo ->beginTransaction();

	//トランザクション
	try
	{
		//プリペアードステートメント
		$stmt = $pdo ->prepare("DELETE FROM trn_rss_libraries
								WHERE id = ?");
		$stmt ->execute(array($buttonURL));
		//ロックのチェック(コミットまでの時間を延長)
		//sleep(5);

      	//コミット
      	$pdo ->commit();
	}
	catch(PDOException $e)
	{
	      //ロールバック
	      $pdo ->rollback();
	      //throw $e;

	    }
