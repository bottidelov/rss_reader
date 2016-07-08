-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- ホスト: 127.0.0.1
-- 生成日時: 2016 年 7 月 08 日 18:01
-- サーバのバージョン: 5.5.27
-- PHP のバージョン: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `rss_reader`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `trn_rss_libraries`
--

CREATE TABLE IF NOT EXISTS `trn_rss_libraries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ユーザーＩＤ',
  `name` varchar(100) NOT NULL COMMENT 'サイトネーム',
  `link` varchar(255) NOT NULL COMMENT 'リンク',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='RSSリンク情報保存テーブル' AUTO_INCREMENT=45 ;

--
-- テーブルのデータのダンプ `trn_rss_libraries`
--

INSERT INTO `trn_rss_libraries` (`id`, `user_id`, `name`, `link`) VALUES
(2, 0, 'Goal.com', 'http://www.goal.com/jp/feeds/news?fmt=rss&ICID=OP'),
(3, 0, 'フットボールチャンネル', 'http://www.footballchannel.jp/feed/'),
(4, 0, 'Qoly', 'http://qoly.jp/feed/rss.xml'),
(5, 0, 'カルチョまとめ', 'http://www.calciomatome.net/index.rdf'),
(6, 0, '長靴を履いた栗鼠', 'http://nagaguturisu.com/feed'),
(7, 0, '後藤健夫コラム', 'http://www.jsports.co.jp/press/column/title/4/rss2.xml'),
(8, 0, '粕谷秀樹のOwn Goal,Fine Goal', 'http://www.jsports.co.jp/press/column/title/60/rss2.xml'),
(9, 0, '元川悦子コラム', 'http://www.jsports.co.jp/press/column/title/3/rss2.xml'),
(11, 0, 'ニコニコ毎時ランキング', 'http://www.nicovideo.jp/newarrival?rss=2.0&lang=ja-jp'),
(28, 0, 'FootBall Lab', 'http://www.football-lab.jp/column.rss'),
(29, 0, 'nanじぇい', 'http://blog.livedoor.jp/g_ogasawara/index.rdf '),
(30, 0, 'わっくんのグラブル日記', 'http://buragenikki.blog.fc2.com/?xml'),
(31, 0, 'ミムメモ速報', 'http://gran-matome.com/feed'),
(32, 0, 'グラブル廃れ雑記', 'http://scahigh.blog58.fc2.com/?xml'),
(33, 0, 'yahoo!トピックス', 'http://news.yahoo.co.jp/pickup/rss.xml'),
(34, 0, 'IT速報', 'http://blog.livedoor.jp/itsoku/index.rdf '),
(36, 0, 'GIGAZIN', 'http://gigazine.net/index.php?/news/rss_2.0/'),
(37, 0, '4亀アケ', 'http://www.4gamer.net/rss/arcade/arcade_news.xml'),
(38, 0, '電撃アーケード', 'http://dengekionline.com/cate/29/rss.xml'),
(39, 0, 'グラブルあんてな', 'http://antenaplus.jp/granblue/feed.rss?cat=1'),
(40, 0, 'PCパーツまとめ', 'http://blog.livedoor.jp/bluejay01-review/index.rdf '),
(41, 0, 'NO FOOTY NO LIFE', 'http://nofootynolife.blog.fc2.com/?xml'),
(42, 0, 'アケゲ速報', 'http://akege.blog.jp/index.rdf'),
(43, 0, 'スマッシュ速報', 'http://smasoku.blog.jp/index.rdf'),
(44, 0, 'ニコニコデイリーランキング', 'http://www.nicovideo.jp/ranking/fav/daily/all?rss=2.0&lang=ja-jp');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
