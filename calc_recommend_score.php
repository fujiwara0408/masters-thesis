<?php
set_time_limit( 0 );

$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'avy8sypd';
$dbname = 'ldclip_fuji';

$connection = mysql_connect($db_server,$db_user,$db_pass);
mysql_select_db($dbname,$connection);
mysql_set_charset('utf8',$connection);

/*推薦スコアを算出してテーブルに格納するプログラム*/
for($Vlimit = 200; $Vlimit>1; $Vlimit=$Vlimit-10){
	$Vlimits = (string)$Vlimit;
	
	$sql0 = sprintf("SELECT u_id FROM t_user WHERE bookmark_num < 500");
	$query0 = mysql_query($sql0,$connection);
	while($row0 = mysql_fetch_array($query0)){
		$u_id = $row0[0];
		//クラスタで共起しているのr_idをすべて取り出す
		$sql2 = sprintf("SELECT r_id FROM l_resorce_tag WHERE t_id = '%d' and r_id IN (SELECT r_id FROM l_resorce_tag WHERE t_id = '%d') ",$t1_id,$t2_id);
		
		$sql1 = sprintf("SELECT c_id,uc_weight FROM t_user_cluster".$Vlimits." WHERE u_id = '%d'",$u_id);
		$query1 = mysql_query($sql1,$connection);
		while($row1 = mysql_fetch_array($query1)){
			$c_id = $row1[0]; 
			$uc_weight = $row1[1]; 
				
			$score = 0;		//初期化
			
			//クラスタとページの重み取得
				$sql2 = sprintf("SELECT r_id,cr_weight FROM l_cluster_resorce_niwa".$Vlimits." WHERE c_id = '%d'",$c_id);
				$query2 = mysql_query($sql2,$connection);
				//クラスタが終わるまで繰り返す
				while($row2 = mysql_fetch_array($query2,MYSQL_NUM)){
					$r_id = $row2[0];
					$cr_weight = $row2[1];
					
					$score = $uc_weight * $cr_weight;
					$sql3 = sprintf("INSERT INTO t_user_resorce_output".$Vlimits."(u_id,r_id,r_score) VALUES('%d','%d','%f')",$u_id,$r_id,$score);
					$query3 = mysql_query($sql3,$connection);
					//mysql_fetch_object($query2);
				}
		}
	}
}
print ("計算終了")
?>