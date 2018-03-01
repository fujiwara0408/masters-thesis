<?php
set_time_limit( 0 );

$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'avy8sypd';
$dbname = 'ldclip_fuji';

$connection = mysql_connect($db_server,$db_user,$db_pass);
mysql_select_db($dbname,$connection);
mysql_set_charset('utf8',$connection);


/*cos類似度を算出してDBに格納するプログラム*/
//3044096
for($tt_id=1; $tt_id<4814256; $tt_id++){

	//タグペアのレコード取得
	$sql1 = sprintf("SELECT * FROM l_tt_sim WHERE tt_id = '%d'",$tt_id); //t1_id,t2_idからレコード
	$query1 = mysql_query($sql1,$connection);
	if($row = mysql_fetch_object($query1)){
		$t1_id = $row->t1_id;		//t1_id
		$t2_id = $row->t2_id;		//t2_id

		$cos_sim = 0;		//初期化
		$t1_norm = 0;
		$t2_norm = 0;
		
		//共通のページ取得
		$sql2 = sprintf("SELECT r_id FROM l_resorce_tag WHERE t_id = '%d' and r_id IN (SELECT r_id FROM l_resorce_tag WHERE t_id = '%d') ",$t1_id,$t2_id); //resorceからレコード
		$query2 = mysql_query($sql2,$connection);
		while($row2 = mysql_fetch_array($query2,MYSQL_NUM)){
			$co_r_id = $row2[0];		//共起ページ取得
			
			//t1_tfidfの取得
			$sql3 = sprintf("SELECT * FROM l_resorce_tag WHERE r_id = '%d' and t_id = '%d'",$co_r_id,$t1_id); 
			$query3 = mysql_query($sql3,$connection);
			$row3 = mysql_fetch_object($query3);
			$t1_tfidf = $row3->rt_tfidf;		//tのページrでのtfidf
			
			//t2_idのtfidf取得
			$sql4 = sprintf("SELECT * FROM l_resorce_tag WHERE r_id = '%d' and t_id = '%d'",$co_r_id,$t2_id); 
			$query4 = mysql_query($sql4,$connection);
			$row4 = mysql_fetch_object($query4);
			$t2_tfidf = $row4->rt_tfidf;		//tのページrでのtfidf
			
			$cos_sim += $t1_tfidf * $t2_tfidf;
		}

		//t1_idのtfidf取得
		$sql3 = sprintf("SELECT * FROM l_resorce_tag WHERE r_id = '%d' and t_id = '%d'",$co_r_id,$t1_id); 
		$query3 = mysql_query($sql3,$connection);
		$row3 = mysql_fetch_object($query3);
		$t1_tfidf = $row3->rt_tfidf;		//tのページrでのtfidf
		$t1_norm += $t1_tfidf * $t1_tfidf;
		
		//t2_idのtfidf取得
		$sql4 = sprintf("SELECT * FROM l_resorce_tag WHERE r_id = '%d' and t_id = '%d'",$co_r_id,$t2_id); 
		$query4 = mysql_query($sql4,$connection);
		$row4 = mysql_fetch_object($query4);
		$t2_tfidf = $row4->rt_tfidf;		//tのページrでのtfidf
		$t2_norm += $t2_tfidf * $t2_tfidf;
		
		
		$cos_sim1 = $cos_sim / (sqrt($t1_norm) * sqrt($t2_norm));
		$sql5 = sprintf("UPDATE l_tt_sim SET r_tfidf_vec_sim = '%f' WHERE tt_id = '%d'",$cos_sim1,$tt_id); //レコード更新
		$query5 = mysql_query($sql5,$connection);
	}
	else{
		continue;
	}
}
print ("計算終了")

?>