<?php
class api_control{
	public function hot_essay_page($system){
		$cache=new cache_tool('./hotessay.cache');
		if($cache->start_page_cache()){
			//echo '<link rel="stylesheet" href="',$system->ini_get('styles_url'),'all.css">';
			echo '<style>* {
	margin: 0px;
	padding: 0px;
	position:relative;
	list-style-type:none;/*清除li带来的点*/
}</style>';
			$e=new essay_server($system);
			$list=$e->get_hot_essay();
			foreach($list as $v){
				echo '<li><a href="',URLROOT,'type/id/',$v['id'],'">',$v['title'],'</a></li>';
			}
			$cache->end_page_cache();
		}
	}
	public function get_pushs_page($system,$c){
		//	var_dump($_SERVER);exit;
		header('Access-Control-Allow-Origin: *');
		$uroot=($_SERVER['SERVER_PROTOCOL']=='HTTP/1.1'?'http://':'https://').$_SERVER['SERVER_NAME'].URLROOT.'essay/id';
		$e=new essay_server($system);
		$list=$e->get_push($c);
		foreach ($list as &$v) {
			$v['url']=$uroot.$v['id'];
			unset($v['content']);
		}
		$system->show_json($list);
	}
}