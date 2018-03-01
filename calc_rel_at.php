<?php
set_time_limit( 0 );

$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'avy8sypd';
$dbname = 'ldclip_fuji';

$connection = mysql_connect($db_server,$db_user,$db_pass);

mysql_select_db($dbname,$connection);
mysql_set_charset('utf8',$connection);

/*testユーザとtagとの関連性を取得するプログラム*/

for($ubt_id=50041; $ubt_id<313023 ;$ubt_id++){ //
	
	$rel_ac = 0;	//初期化
	//testユーザのidを順番に取り出す
	$sql0 = sprintf("SELECT u_id,t_id FROM t_user_b_tag where ubt_id = '%d'",$ubt_id);
	$query0 = mysql_query($sql0,$connection);
	$row0 = mysql_fetch_array($query0,MYSQL_NUM);
	$u_id = $row0[0];
	$t_id = $row0[1];
		
	//そのtestユーザがブックマークしているページのidを取り出す
	$sql1 = sprintf("SELECT r_id FROM t_user_resorce_input WHERE u_id = '%d'",$u_id);
	$query1 = mysql_query($sql1,$connection);
	while($row1 = mysql_fetch_array($query1,MYSQL_NUM)){
		$r_id = $row1[0];
		
		//そのブックマークしているページの中でタグt_idがあれば加算していく
		$sql2 = sprintf("SELECT rt_tfidf FROM l_resorce_tag WHERE r_id = '%d' and t_id = '%d'",$r_id, $t_id);
		$query2 = mysql_query($sql2,$connection);
		if($row2 = mysql_fetch_array($query2,MYSQL_NUM)){
			$rel_ac += $row2[0];
			//echo $rel_ac;
			//echo "<br>";
		}
	}
	$sql3 = sprintf("UPDATE t_user_b_tag SET rel_at='%f' WHERE ubt_id = '%d'",$rel_ac,$ubt_id);
	$query3 = mysql_query($sql3,$connection);
		//rel_acを更新
	//	echo "<br>";
		
}

print ("計算終了")

?>