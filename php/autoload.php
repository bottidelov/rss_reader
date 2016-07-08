<?php

//インスタンス生成の際、該当するクラスが存在しない際呼び出される
//引数は呼び出そうとするクラス名が自動で渡される。

spl_autoload_register(function($name){
	include __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';
});
//__autoload関数、現在非推奨
/*
function __autoload($hoge){
	require_once "{$hoge}.php";
}
*/

