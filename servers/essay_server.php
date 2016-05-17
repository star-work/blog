<?php
class essay_server{
	const sh='div:;p:;br:;a:href;img:src,width,height';
	private $system;
	const table='@%_essay';
	const push_table='@%_push';
	private $table;
	public static $_display=array('隐藏','公开');
	public static function display($i){
		return self::$_display[$i];
//		var_dump(self::$_display);
	}
	public function __construct($system){
		$this->system=$system;
	}
	public function get_list_with_content($limit){
		$limit+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`title`,`time`,`content`,`display` FROM `'.self::table.'` ORDER BY `time` DESC limit '.$limit);
	}
	public function get_list($page,$limit=20){
		$limit+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`title`,`time`,`sender`,`display` FROM `'.self::table.'` ORDER BY `time` DESC limit '.($page-1)*$limit.','.$limit);
	}
	public function get_push($type,$limit=10){
		$type+=0;$limit+=0;
		return $this->system->db()->do_SQL('SELECT `e`.`id`,`e`.`title`,`e`.`time`,`e`.`content`,`e`.`display` FROM `'.self::table.'` as `e` JOIN `'.self::push_table.'` ON `e`.`id`=`'.self::push_table.'`.`essay` WHERE `'.self::push_table.'`.`type`='.$type.' LIMIT '.$limit);
	}
	public function get_page($limit=20){
		$limit+=0;
		$res=$this->system->db()->do_SQL('SELECT COUNT(`id`) AS `nu` FROM `'.self::table.'`');
		return ceil($res[0]['nu']/$limit);
	}
	public function add($title,$content,$sender,$display=1,$keywords=''){
		$title=htmlentities($title,ENT_NOQUOTES,'utf-8');
		$content=htmlentities($content,ENT_NOQUOTES,'utf-8');
		$this->system->db()->u_do_SQL('INSERT INTO `'.self::table.'`(`title`,`time`,`content`,`sender`,`display`,`keywords`) VALUE(?,?,?,?,?,?)',array($title,time(),$content,$sender,$display,$keywords));
	}
	public function add_push($eid,$type){
		$eid+=0;$type+=0;
		$this->system->db()->do_SQL('INSERT INTO `'.self::push_table.'`(`type`,`essay`) VALUE('.$type.','.$eid.')');
	}
	public function in($title,$content,$uid,$time,$display,$hot){
		$title=htmlentities($title,ENT_NOQUOTES,'utf-8');
		$content=htmlentities($content,ENT_NOQUOTES,'utf-8');
		$this->system->db()->u_do_SQL('INSERT INTO `'.self::table.'`(`title`,`time`,`content`,`sender`,`display`) VALUE(?,?,?,?,?)',array($title,$time,$content,$uid,$display));
	}
	public function update($id,$title,$content,$keywords){
		$this->system->db()->u_do_SQL('UPDATE `'.self::table.'` SET `title`=?,`content`=?,`keywords`=? WHERE `id`=?',array($title,htmlentities($content,ENT_NOQUOTES,'utf-8'),$keywords,$id,));
	}
	public function get_by_id($id){
		$id+=0;
		$this->system->db()->do_SQL('UPDATE `'.self::table.'` SET `hot`=`hot`+1 WHERE `id`='.$id);//访问增加热度
		$res=$this->system->db()->do_SQL('SELECT `display`,`title`,`content`,`time`,`keywords` FROM `'.self::table.'` WHERE `id`='.$id.' LIMIT 1');
		return $res?$res[0]:null;
	}
	public function get_max_id(){
		$res=$this->system->db()->do_SQL('SELECT max(`id`) AS `max` FROM `'.self::table.'`');
		return $res[0]['max'];
	}
	public function get_for_out($start,$limit){
		$start+=0;$limit+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`title`,`time`,`content`,`display`,`sender`,`hot` FROM `'.self::table.'` ORDER BY `time` DESC limit '.$start.','.$limit);
	}
	public function get_hot_essay($limit=5){
		$limit+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`title` FROM `'.self::table.'` ORDER BY `hot` DESC LIMIT '.$limit);
	}
}