<?php
set_time_limit( 0 );

$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'avy8sypd';
$dbname = 'ldclip_fuji';

$connection = mysql_connect($db_server,$db_user,$db_pass);
mysql_select_db($dbname,$connection);
mysql_set_charset('utf8',$connection);


for($Vlimit = 140; $Vlimit>21; $Vlimit=$Vlimit-10){
	$Vlimits = (string)$Vlimit;

	//for($uc_id=1; $uc_id<164195 ;$uc_id++){
	$sql0 = sprintf("SELECT uc_id FROM t_user_cluster".$Vlimits);
	$query0 = mysql_query($sql0,$connection);
	//uc_idが終わるまで繰り返す
	while($row0 = mysql_fetch_array($query0,MYSQL_NUM)){
		$uc_id = $row0[0];
		$rel_ac = 0;		//初期化
		//ユーザとクラスタのid取得
		$sql1 = sprintf("SELECT u_id,c_id FROM t_user_cluster".$Vlimits." WHERE uc_id = '%d'",$uc_id);
		$query1 = mysql_query($sql1,$connection);
		if($row1 = mysql_fetch_array($query1,MYSQL_NUM)){
			$u_id = $row1[0];		//u_id
			$c_id = $row1[1];		//t_id
			//echo $u_id;
			//echo "aaa";
		}
		//クラスタ内のタグid取得
		$sql2 = sprintf("SELECT t_id FROM niwa_cluster WHERE cluster".$Vlimits." = '%d'",$c_id);
		$query2 = mysql_query($sql2,$connection);
		//タグが終わるまで繰り返す
		while($row2 = mysql_fetch_array($query2,MYSQL_NUM)){
			$t_id = $row2[0];
			
				//echo $r_id.'   ';
				
				//rel_at取得
			$sql3 = sprintf("SELECT rel_at FROM t_user_b_tag WHERE u_id = '%d' and t_id = '%d'",$u_id,$t_id);
			$query3 = mysql_query($sql3,$connection);
			if($row3 = mysql_fetch_array($query3,MYSQL_NUM)){
				$rel_at = $row3[0];
				$rel_ac += $rel_at;
			}
		}
		//レコード更新
		$sql4 = sprintf("UPDATE t_user_cluster".$Vlimits." SET uc_weight = '%f' WHERE uc_id = '%d'",$rel_ac,$uc_id); 
		$query4 = mysql_query($sql4,$connection);
	}
		/*else{
			continue;
		}*/
		//$tp_id++;
}
print ("計算終了")

?>