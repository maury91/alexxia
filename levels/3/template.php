<?php
/*
	TODO : finire regole!
*/
/**
 *	Permissions for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
class MENU {
	private static $menus=null,$menus_l2=null,$rulez=null;
	
	static private function rule_m($rule,$i,$tot) {
	}
	
	static private function rule_l($rule) {
	}
	
	static private function mod($name) {
		ob_start();
		if (file_exists(__base_path.'mod/'.$name.'/mod.php'))
			include(__base_path.'mod/'.$name.'/mod.php');
		return ob_get_clean();
	}
	
	static private function build($name,$menu,$to_cache=false) {
		$to_ret='';
		if (self::$rulez) {
			$rulez_set = self::$rulez->$name;
			$tot = count($menu);
			for ($i=0;$i<$tot;$i++) {
				if ($menu[$i]['type']) {
					foreach ($rulez_set->link as $rl) {
						if ((isset($rl->rule))?self::rule_m($rl->rule,$i,$tot):true) {
							$links='';
							$tot2=count($menu[$i]['links']);
							for ($j=0;$j<$tot2;$j++) {
								foreach ($rl->links as $rll) {
									if ((isset($rll->rule))?self::rule_l($rll->rule,$j,$tot2):true) {
										$cla = (trim($menu[$i]['links'][$j]['class'].(isset($rll->{'class'})?$rll->{'class'}:''))=='')?'':' class="'.$menu[$i]['links'][$j]['class'].' '.(isset($rll->{'class'})?$rll->{'class'}:'').'" ';
										if (strpos('.'.$menu[$i]['links'][$j]['href'],'http://')!==1)
											$menu[$i]['links'][$j]['href'] = __http_host.__http_path.$menu[$i]['links'][$j]['href'];
										$links.=str_ireplace(array('%class%','%text%','%image%','%href%','%html%'),
											array($cla,
												$menu[$i]['links'][$j]['text'],
												$menu[$i]['links'][$j]['image'],
												$menu[$i]['links'][$j]['href'],
												$menu[$i]['links'][$j]['html']),
										$rll->content);
									}
								}
							}
							$cla = (trim($menu[$i]['class'].(isset($rl->{'class'})?$rl->{'class'}:''))=='')?'':' class="'.$menu[$i]['class'].(isset($rl->{'class'})?$rl->{'class'}:'').'" ';
							$to_ret.=str_ireplace(array('%class%','%text%','%links%','%image%','%html%'),array($cla,$menu[$i]['text'],$links,$menu[$i]['image'],$menu[$i]['html']),$rl->content);
						}
					}
				} else {
					foreach ($rulez_set->mod as $rl) {
						if ((isset($rl->rule))?self::rule_m($rl->rule,$i,$tot):true) {
							if ($to_cache)
								$mod='%mod-'.$menu[$i]['module'].'-mod%';
							else
								$mod=self::mod($menu[$i]['module']);
							$cla = (trim($menu[$i]['class'].(isset($rl->{'class'})?$rl->{'class'}:''))=='')?'':' class="'.$menu[$i]['class'].(isset($rl->{'class'})?$rl->{'class'}:'').'" ';
							$to_ret.=str_ireplace(array('%class%','%text%','%mod%','%image%','%html%'),array($cla,$menu[$i]['text'],$mod,$menu[$i]['image'],$menu[$i]['html']),$rl->content);
						}
					}
				}
			}
		} else {
		}
		return $to_ret;
	}

	static public function load_menus() {
		if (self::$menus==null) {
			if (file_exists(__base_path.'cache/menu/c2/')) {
				self::$menus=true;
				include(__base_path.'cache/menu/c2/'.(LANG::short()).'_'.(USER::level()).'.php');
				self::$menus_l2=$menu;
			} else {
				//Regole
				if (file_exists(__base_path.'template/'.GLOBALS::val('template').'/build.json'))
					self::$rulez = json_decode(file_get_contents(__base_path.'template/'.GLOBALS::val('template').'/build.json'));
				else
					self::$rulez = false;
				if (file_exists(__base_path.'cache/menu/c1/')) {
					include(__base_path.'cache/menu/c1/'.(LANG::short()).'_'.(USER::level()).'.php');
					self::$menus=$menu;
				} else {
					//Caricamento dati
					mkdir(__base_path.'cache/menu/c1/');
					include(__base_path.'config/menu.php');
					if (file_exists(__base_path.'template/'.GLOBALS::val('template').'/menu.json')) {
						$men_conf = json_decode(file_get_contents(__base_path.'template/'.GLOBALS::val('template').'/menu.json'));
						$men2=array();
						foreach ($men_conf->menus as $v) {
							if (isset($menu[$v->name])) {
								$men2[$v->name] = $menu[$v->name];
								unset($menu[$v->name]);
							}
							if (!isset($men2[$v->name]))
								$men2[$v->name] = array();
							if (isset($v->absorbe)) {
								foreach($v->absorbe as $j) {
									$men2[$v->name] = array_merge($men2[$v->name],$menu[$j]);
									unset($menu[$j]);
								}
							}
							if (isset($v->accept)) {
								if (strtolower($v->accept)=='links') {
									if (!isset($men2[$men_conf->move->mod]))
										$men2[$men_conf->move->mod]=array();
									foreach ($men2[$v->name] as $k => $v) {
										if (!$v['type']) {
											$men2[$men_conf->move->mod][] = $v;
											array_splice($men2[$v->name],$k,1);
										}
									}
								} elseif (strtolower($v->accept)=='mod') {
									if (!isset($men2[$men_conf->move->links]))
										$men2[$men_conf->move->links]=array();
									foreach ($men2[$v->name] as $k => $v) {
										if ($v['type']) {
											$men2[$men_conf->move->links][] = $v;
											array_splice($men2[$v->name],$k,1);
										}
									}
								}
							}
							if (isset($v->move)) {
								if (isset($v->move->mod)) {
									if (!isset($men2[$v->move->mod]))
										$men2[$v->move->mod]=array();
									foreach ($men2[$v->name] as $k=>$v) {
										if (!$v['type']) {
											$men2[$v->move->mod][] = $v;
											array_splice($men2[$v->name],$k,1);
										}
									}
								}
								if (isset($v->move->links)) {
									if (!isset($men2[$v->move->links]))
										$men2[$v->move->links]=array();
									foreach ($men2[$v->name] as $k=>$v) {
										if ($v['type']) {
											$men2[$v->move->links][] = $v;
											array_splice($men2[$v->name],$k,1);
										}
									}
								}
							}
						}
						//COntrollo menu
						foreach ($menu as $v) 
							foreach ($v as $j) {
								if ($j['type']) {
									foreach ($j['links'] as $a => $b)
										if (strpos('.'.$b['href'],'http://')!==1)
											$j['links'][$a]['href'] = __http_host.__http_path.$j['links'][$a]['href'];
									$men2[$men_conf->move->links][] = $j;
								} else
									$men2[$men_conf->move->mod][] = $j;
							}							
						$men3 = array();
						for ($l=0;$l<11;$l++) {
							$men3[$l] = $men2;
							foreach ($men3[$l] as $k => $v) {
								foreach ($v as $j => $i) {
									if (($i['level']< $l)||(($i['level']==11)&&($l<10)))
										unset($men3[$l][$k][$j]);
									else {
										if ($i['type']) {
											foreach ($i['links'] as $a => $b) {
												if (($b['level']< $l)||(($b['level']==11)&&($l<10)))
													unset($men3[$l][$k][$j]['links'][$a]);
											}
											$men3[$l][$k][$j]['links'] = array_values($men3[$l][$k][$j]['links']);
										}
									}
								}
								$men3[$l][$k] = array_values($men3[$l][$k]);
							}
						}
						$men4 = array();
						$__langs = LANG::get_list();
						foreach ($__langs as $lk=>$lv) {
							$men4[$lk]=$men3;
							for ($l=0;$l<11;$l++) {
								foreach ($men3[$l] as $k => $v)  {
									foreach ($v as $j => $i) {
										if (($i['lang']!='all')&&($i['lang']!=$lk))
											unset($men4[$lk][$l][$k][$j]);
										else {
											if ($i['type']) {
												foreach ($i['links'] as $a => $b) {
													if (($b['lang']!='all')&&($b['lang']!=$lk))
														unset($men4[$lk][$l][$k][$j]['links'][$a]);
												}
												$men4[$lk][$l][$k][$j]['links'] = array_values($men4[$lk][$l][$k][$j]['links']);
											}
										}
									}
									$men4[$lk][$l][$k] = array_values($men4[$lk][$l][$k]);
								}
								file_put_contents(__base_path.'cache/menu/c1/'.$lk.'_'.$l.'.php',"<?php\n".'$menu = '.var_export($men4[$lk][$l],true).";\n?>");	
							}
						}				
						self::$menus=$men4[LANG::short()][USER::level()];
						//Cache L2!
						if (isset($men_conf->cache)&&($men_conf->cache>1)) {
							mkdir(__base_path.'cache/menu/c2/');
							$men_l2 = array();
							foreach ($__langs as $lk=>$lv) 
								for ($l=0;$l<11;$l++) {
									foreach ($men4[$lk][$l] as $k => $v)
										$men_l2[$lk][$l][$k] = self::build($k,$v,true);
									file_put_contents(__base_path.'cache/menu/c2/'.$lk.'_'.$l.'.php',"<?php\n".'$menu = '.str_replace(array('%mod-','-mod%'),array('\'.self::mod(\'','\').\''),var_export($men_l2[$lk][$l],true)).";\n?>");
								}
							self::$menus_l2=$men_l2[LANG::short()][USER::level()];
						}
					}
				}
			}
		}
	}

	static public function get($menu) {
		self::load_menus();
		if (self::$menus_l2==null)
			return self::build($menu,self::$menus[$menu]);
		else
			return self::$menus_l2[$menu];
	}
}
class TEMPLATE {
	
	private static function compile_template($template) {
		define('template_path',__http_host.__http_path.'template/'.$template.'/');
		$y = file_get_contents(__base_path.'template/'.$template.'/index.html');
		$y = str_ireplace(
			array('<ALE::LOGO/>','<ALE::HEAD/>','<ALE::PAGE/>','<ALE:TEMPLATE/>','<ALE:COPYRIGHTS/>','<ALE:HOME/>'),
			array('<?php echo HTML::get_logo(); ?>','<?php echo HTML::get_head(); ?>','<?php echo $html; ?>',template_path,'<a href="http://niicms.net" target="_blank">Powered by Alexxia Open Source Content Manager</a>',__http_host.__http_path),$y);
		//Rilevazione del tag <body>
		$pos = stripos($y,'<body');
		$pos2= strpos(substr($y,$pos+5),'>');
		$y = substr($y,0,$pos+5).'<?php echo HTML::get_body_tag(); ?>'.substr($y,$pos+5,$pos2+1)."\n\t\t".'<?php echo HTML::get_body(); ?>'.substr($y,$pos+5+$pos2+2);
		//Rilevazione menu
		do {
			$pos = stripos($y,'<ALE::MENU',$pos+10);
			$pos2 = stripos($y,'/>',$pos+10);
			if ($pos!==false) {
				$data = substr($y,$pos+10,$pos2-$pos-10);
				$name = trim(strstr(stristr($data,'name'),'"')," \t\n\r\0\x0B\"");
				$y = substr($y,0,$pos).'<?php echo MENU::get(\''.$name.'\') ?>'.substr($y,$pos2+2);
			}
		} while($pos!==false);
		file_put_contents(__base_path.'cache/template.php',$y);
	}

	public static function elab($html) {
		if (!file_exists(__base_path.'cache/template.php'))
			self::compile_template(GLOBALS::val('template'));
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			ob_start('ob_gzhandler');
		include __base_path.'cache/template.php';
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			ob_flush();
	}
}
?>
