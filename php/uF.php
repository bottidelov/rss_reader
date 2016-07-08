<?php
//namespace php\My;
//名前空間※上の行に<?php以外の何者も存在してはいけない。
//※名前空間が宣言された時点でグローバル領域から個別の領域が生成される
//よって配下クラスにアクセスする場合、名前空間\クラス名でアクセス
//また名前空間内から外にアクセスする場合、\をつける

//ユーザー定義関数をまとめたクラス
//abstractメソッドを用いる場合、classにもabstractを付与する
//また抽象クラスはインスタンス生成できない = 子クラスをインスタンス化する
/*abstract*/ class uF
{

/*OPPテスト用関数郡(ほか継承用クラスあり)*******************************/

	//クラスプロパティのテスト用
	//オーバーライドを禁止する場合はfinal修飾子を付与する
	public /*final*/ $a = "てすてす";
	//protectedには子クラス(ゲッタ・セッタメソッドでのみアクセスできる)
	protected $aa = "うすうす";
	private $aaa = "おっすおっす";
	public $aaaa = "";

	//クラスメソッド
	public function test(){
		echo "クラスメソッドdeath!";
	}

	/************
	  	コンストラクタのテスト用
	    コンストラクタ = インスタンス化の際に実行されるメソッド
		※コンストラクタは値を返してはいけない(あくまで値設定の為に使用する)
		主にプロパティ値の初期化等につかわれる
		また、特定のタイミングで自動で実行されるメソッドをマジックメソッドという
		※コンストラクタもマジックメソッドに含まれ、__XXXと書かれる
		あくまで実行タイミングのみで、自身で定義する(中身はカラ)
	************/
	public function __construct(){
		$b = $this->a + "てふてふ";
	}

	//クラス定数、静的プロパティよりこちらを推奨
	const deff = "てーすー";

	//ゲッタメソッド・外部よりクラスのメンバ変数にアクセスする際に使用するメソッド
	public function getDeff(){
		return $this->aa;
	}

	//セッターメソッド・外部よりクラスにメンバ変数にセットする際に使用するメソッド
	//※「メソッド」なので処理を加えることもできる
	public function setDeff($arg){
		if(is_string($arg) && $arg !== "")
		{
			$this->aaaa = $arg;
		}
	}

	//マジックメソッド・__set,__get(定義されていない関数が呼ばれた際実行される)
	//定義されいない関数が呼ばれた際、以下の変数に格納される
	private $errBox = array();

	public function __set($key, $value){
	echo "__set()なう\n";
	//連想配列として格納
	$this->errBox[$key] = $value;
	}

	public function __get($key){
	echo "__get()なう\n";
	//連想配列から$nameに対応する$valueを返す
	return $this->errBox[$key];
	}

	//抽象メソッド(子クラスにオーバーライドされる事を予定した空のメソッド)
	//※抽象メソッドは中に機能を定義することはできない。また空のままだとエラーを吐く
	//public abstract function getAbst();

	/*
	public static function showNamespace(){
		echo "名前空間は".__NAMESPACE__."です！";
	}*/

/******************************************/

	//DB接続関数
	public static function getPDO()
	{
		//$dsn = 'mysql:dbnam=localhost; host=127.0.0.1; port=3306'; #変数による設定
		//$user = 'root';
		//$passwd = 'selfphp';

		//-> アロー演算子 クラスのメソット・プロパティにアクセスする際に用いる、
		//親クラスをインスタンスした変数に用いる(クラスは型、インスタンスは実態)

		//:: スコープ演算子 staticを設定したメソッド・プロパティに用いる
		//static(静的)メソッド = 親クラスをインスタンス化しなくても利用できる
		//静的メソッド内では$thisは使えない、代わりにself::を用いる

		//定数の設定
		//ローカル・社内ローカルサーバー

		define("HOST", "localhost"); // データベースサーバのアドレス
		define("DBNAME", "rss_reader"); // データベースの名前
		define("DBUSER", "root"); // データベース利用ユーザの名前
		define("DBPASS", "");  // データベース利用ユーザのパスワード

		try
		{ //例外処理 try～catch文

			$pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, DBUSER, DBPASS); #定数版
			//echo var_dump($pdo);
			//エラー処理構文
			$pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo ->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			//トランザクション処理を開始(DB側のオートコミットをオフにする)
			$pdo ->beginTransaction();
			//トランザクション分離レベル変更(mysqlのデフォルトは REPEATABLE READ)
			//$pdo ->exec("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
			//文字コード設定
			$pdo ->exec('SET NAMES utf8');
			//echo "接続成功です！";

		}
		catch (PDOException/*PDOの例外、自発でスローしてはいけない*/ $e)
		{
			//発生する例外の種類,例外を入れる変数
			die("接続エラー:{$e ->getMessage()}");
			//$eからgetMessageメソッドを出力して終了
		}
		return $pdo;
	}

    //XSS対策
    public static function h($str, $charset = "utf-8")
    {
        return htmlspecialchars($str, ENT_QUOTES, $charset);
    }

    //メール送信
    public static function toMail($toMail,$mailTitle,$mailContent,$mAddress)
    {
	    //現在の言語を設定
	    mb_language("Japanese");
	    //内部文字エンコーディングを設定
	    mb_internal_encoding("UTF-8");
	    //送信先アドレス
	    $to_mail_address = $toMail;
	    //メールタイトル
	    $mail_title = $mailTitle;
	    //メール本文
	    $mail_contents = $mailContent;
	    //文字化け対策に本文の文字コードを変換
	    $enc_mail_contents = mb_convert_encoding($mail_contents,'ISO-2022-JP');
	    //送信元アドレス
	    $from_mail_address = $mAddress;
	    //メールの送信
	    if (mb_send_mail($to_mail_address, $mail_title,  $enc_mail_contents, "From: " . $from_mail_address)) {
	    	//echo "メールが送信されました。";
	    } else {
	    	//echo "メールの送信に失敗しました。";
	    }
    }

    //バリデーション ※この後にflagが正の際の挙動を書く
    public static function val($name,$mail)
    {
	    #バリデーションフラグ
	    $val_flag = TRUE;

	    #スペースのみの入力をはじく正規表現
	    $space = "^(\s|　)+$";

	    #バリデーション
	    if ($name === "" or  mb_ereg_match($space,$name )){
	    	$_SESSION["message"] = "※氏名を入力してください";
	    	$val_flag = FALSE;
	    }
	    if ($mail === "" or  mb_ereg_match($space,$mail)){
	    	$_SESSION["message"] = "※メールアドレスを入力してください";
	    	$val_flag = FALSE;
	    }elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
	    	$_SESSION["message"] = "※正しい形式のメールアドレスを入力してください";
	    	$val_flag = FALSE;
	    }elseif(mb_strlen($mail, "utf8") > 50){
	    	$_SESSION["message"] = "※メールアドレスは50文字以下にしてください";
            $val_flag = FALSE;
        }
	    return $val_flag;
    }

    //バリデーション ※この後にflagが正の際の挙動を書く、
    //第一・第二引き数っをメッセージ、以降を引き数に登録
    public function valid()
    {
    	//バリデーションフラグ
    	$val_flag = TRUE;

    	//スペースのみの入力をはじく正規表現
    	$space = "^(\s|　)+$";

    	//文頭・文末の空欄を削除
    	$val_args  = trim($val_args);

    	//バリデーションにかける引数を配列にして登録(可変引数)
    	$val_args = func_get_args();

    	//バリデーション・空欄チェック
    	if ($val_args[2] === "" or  mb_ereg_match($space,$val_args[2])){
    		$_POST["message"] = $val_args[0];
    		return $val_flag = FALSE;
    	}elseif(mb_strlen($val_args[2] , "utf8") > 25){
    		$_POST["message"] =$val_args[1];
            return $val_flag = FALSE;
    	}

    	//長さチェック
    	if ($val_args[3] === "" or  mb_ereg_match($space,$val_args[3]))
    	{
    		$_POST["message"] = $val_args[0];
    		return $val_flag = FALSE;
    	}elseif(mb_strlen($val_args[3] , "utf8") > 100){
    		$_POST["message"] =$val_args[1];
    		return $val_flag = FALSE;
    	}

    	//てすてす
    	//var_dump($this->a);

    	//戻り値の出力
    	return $val_flag;
    }

    //文字長の判定
    public static function checkLength($tar,$max)
    {
        //変数の長さを取得
        $length = mb_strlen("$tar");
        if ( $length <= $max ) {
            return 1;
        }
        else{
            return -1;
        }
    }
    
    //json形式へのエンコード
    public static function json_safe_encode($data){
    	return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP |JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    //47県・セレクトボックス表示
    function ken_list($TAR)
    {
        $Ken= array("北海道","青森","岩手","宮城","秋田","山形","福島","茨城","栃木","群馬","埼玉","千葉","東京","神奈川","新潟","富山","石川","福井","山梨","長野","岐阜","静岡","愛知","三重","滋賀","京都","大阪","兵庫","奈良","和歌山","鳥取","島根","岡山","広島","山口","徳島","香川","愛媛","高知","福岡","佐賀","長崎","熊本","大分","宮崎","鹿児島","沖縄","その他");

        for($i=0 ;$i<48 ;$i++){
            $select = "";
            if($i == $TAR){
                $select = "selected";
            }
            echo("<option value=\"$i\" $select>$Ken[$i]</option>");
        }
    }


    //時差の算出
    public static function diffTime($start,$end)
    {
    	$day1 = new DateTime($start);
    	$day2 = new DateTime($end);

    	$interval = $day2->diff($day1);

    	$time = $interval->format('%h時間 %i分 %s秒');
    	return $time;
    }

    //unixタイムスタンプに変換
    public static  function  unixTime($year,$month,$day)
    {
    	$tmp = date("U",mktime(0,0,0,$month,$day,$year));
    	return $tmp;
    }

    //datetimeを日時のみに整形(DBのソートに使用)
    public static function cutDate($d){
    	$fix = date('Y-m-d', strtotime($d));
    	return $fix;
    }
    //datetimeを日時年月の表示へ整形
    public static function fixDate($d){
    	$fix = date('Y年m月d日 H時i分', strtotime($d));
    	return $fix;
    }
    //datetimeを年度のみ表示へ整形
    public static function fixYear($d){
    	$fix = date('Y', strtotime($d));
    	return $fix;
    }
    //datetimeを年度のみ表示へ整形(下二桁のみ)
    public static function fixYear_c($d){
    	$fix = date('y', strtotime($d));
    	return $fix;
    }
    //datetimeを月のみ表示へ整形
    public static function fixMonth($d){
    	$fix = date('m', strtotime($d));
    	return $fix;
    }
    //datetimeを月のみ表示へ整形(値の前に0をつけない)
    public static function fixMonth_c($d){
    	$fix = date('n', strtotime($d));
    	return $fix;
    }
    //datetimeを日時のみ表示へ整形
    public static function fixDays($d){
    	$fix = date('d', strtotime($d));
    	return $fix;
    }
    //datetimeを日時のみ表示へ整形(値の前に0をつけない)
    public static function fixDays_c($d){
    	$fix = date('j', strtotime($d));
    	return $fix;
    }
    //datetimeを時刻のみ表示へ整形
    public static function fixHours($d){
    	$fix = date('H', strtotime($d));
    	return $fix;
    }
    //datetimeを分のみ表示へ整形
    public static function fixMinutes($d){
    	$fix = date('i', strtotime($d));
    	return $fix;
    }
}

//継承
class core_uF extends uF{
	//オーバーライド
	public $a = "けいしょうてすてす";

	//親クラスのメソッドに追加
	//parentキーワード
	public function test(){
		parent::test();
		echo "or HELL!";
	}

	//抽象メソッドのオーバーライド
	/*public function getAbst(){
		echo "これが抽象！";
	}*/

	/*またひとつの親クラスから分岐したふたつのクラスの同名メソッドは、
	 それぞれ別のメソッドとして機能する = 多様性(ポリモーフィズム)*/
}

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//セッション期限の変更および使用宣言
//セッションのキャッシュ期限を半日に設定(デフォルトは180)
//session_cache_expire(43200);
session_start();

//HTTPヘッダ設定
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

