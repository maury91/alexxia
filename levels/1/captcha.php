<?php
CLASS CAPTCHA {
	private $items,$id=-1,$type=-1,$w,$h,$center,$size;

	public function __construct($id=null,$items=5,$w=200,$h=150) {
		@session_start();
		if (is_null($id)) {
			$this->items = $items;
			$this->w = $w;
			$this->h = $h;
		} elseif (isset($_SESSION['captcha'][$id])) {
			$data = $_SESSION['captcha'][$id];
			$this->id=$id;
			$this->type=$data['type'];
			$this->size=$data['size'];
			$this->center=$data['center'];
			$this->w=$data['w'];
			$this->h=$data['h'];
			$this->items=$data['items'];
		}
		HTML::add_script('js/captcha.js');
		HTML::add_style('css/captcha.css');
	}
	
	private function triangle_vertexs($center,$radius) {
		$v1 = array(0,-$radius);
		$v2 = array($center['x']+$v1[0]*cos(2/3*M_PI)-$v1[1]*sin(2/3*M_PI),$center['y']+$v1[0]*sin(2/3*M_PI)+$v1[1]*cos(2/3*M_PI));
		$v3 = array($center['x']+$v1[0]*cos(4/3*M_PI)-$v1[1]*sin(4/3*M_PI),$center['y']+$v1[0]*sin(4/3*M_PI)+$v1[1]*cos(4/3*M_PI));
		$v1 = array($center['x'],$center['y']-$radius);
		return array($v1,$v2,$v3);
	}
	
	private function generate($id=null) {
		if (($this->id==-1)||(!is_null($id))) {
			if (is_null($id))
				$this->id = RAND::word();
			$this->type=rand()%18;
			$this->size=rand()%(min($this->w,$this->h)/4+20)+(min($this->w,$this->h)/4);
			$this->center=array('x'=>$this->size+rand()%($this->w-$this->size*2),'y'=>$this->size+rand()%($this->h-$this->size*2));
			$_SESSION['captcha'][$this->id] = array('type'=>$this->type,'size'=>$this->size,'center'=>$this->center,'w'=>$this->w,'h'=>$this->h,'items'=>$this->items);
		}
	}
	
	private function tsgn($p1,$p2,$p3) {
		return ($p1[0]-$p3[0])*($p2[1]-$p3[1])-($p2[0]-$p3[0])*($p1[1]-$p3[1]);
	}
	
	public function click($x,$y) {
		if ($this->id==-1)
			return false;
		unset($_SESSION['captcha'][$this->id]);
		switch ($this->type) {
			case 0 :
			case 1 :
			case 8 :
			case 9 :
			case 10 :
			case 15 :
				//Cerchio (distanza (x,y)-(centro) < raggio)
				$ret = ((pow($x-$this->center['x'],2)+pow($y-$this->center['y'],2))<pow($this->size/2,2));
			break;			
			case 2 :
			case 3 :
			case 6 :
			case 11 :
			case 12 :
			case 16 :
				//Quadrato
				$ret = (abs($x-$this->center['x'])<($this->size/2))&&(abs($y-$this->center['y'])<($this->size/2));
			break;
			case 4 :
			case 5 :
			case 7 :
			case 13 :
			case 14 :
			case 17 :
				//Triangolo				
				//Calcolo vertici
				$v = $this->triangle_vertexs($this->center,$this->size);
				$b1 = $this->tsgn(array($x,$y),$v[0],$v[1])<0;
				$b2 = $this->tsgn(array($x,$y),$v[1],$v[2])<0;
				$b3 = $this->tsgn(array($x,$y),$v[2],$v[0])<0;
				$ret = ($b1==$b2)&&($b2==$b3);				
			break;
		}
		if (!$ret)
			$this->generate($this->id);
		return $ret;
	}
	
	public function draw_circle($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		imageellipse($img,$pos['x'],$pos['y'],$size,$size,$col);
	}
	
	public function draw_circle_open($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		imagearc($img,$pos['x'],$pos['y'],$size,$size,rand()%120,rand()%200+160,$col);
	}
	
	public function draw_square($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		$size/=2;
		imagerectangle($img,$pos['x']-$size,$pos['y']-$size,$pos['x']+$size,$pos['y']+$size,$col);
	}
	
	public function draw_square_open($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		$col2 = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		$lat=rand()%4;
		$size/=2;
		$vs=array(
			array($pos['x']-$size,$pos['y']-$size),
			array($pos['x']+$size,$pos['y']-$size),
			array($pos['x']+$size,$pos['y']+$size),
			array($pos['x']-$size,$pos['y']+$size),
			array($pos['x']-$size,$pos['y']-$size)
		);
		for ($i=0;$i<4;$i++) {
			if ($lat==$i) {
				//imageline($img,$vs[$i][0],$vs[$i][1],rand()%max(abs($vs[$i+1][0]-$vs[$i][0]),1)+min($vs[$i+1][0],$vs[$i][0]),rand()%max(abs($vs[$i+1][1]-$vs[$i][1]),1)+min($vs[$i+1][1],$vs[$i][1]),$col);
			} else
				imageline($img,$vs[$i][0],$vs[$i][1],$vs[$i+1][0],$vs[$i+1][1],$col);
		}
	}
	
	public function draw_triangle($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		$v = $this->triangle_vertexs($pos,$size);
		imageline($img,$v[0][0],$v[0][1],$v[1][0],$v[1][1],$col);
		imageline($img,$v[2][0],$v[2][1],$v[1][0],$v[1][1],$col);
		imageline($img,$v[0][0],$v[0][1],$v[2][0],$v[2][1],$col);
	}
	
	public function draw_triangle_open($img,$size,$pos=null) {
		$col = imageColorAllocate($img, rand()%256, rand()%256, rand()%256);
		if ($pos==null)
			$pos=array('x'=>$size+rand()%max(($this->w-$size*2),1),'y'=>$size+rand()%max(($this->h-$size*2),1));
		$v = $this->triangle_vertexs($pos,$size);
		$lat = rand()%3;
		if ($lat!=0)
			imageline($img,$v[0][0],$v[0][1],$v[1][0],$v[1][1],$col);
		if ($lat!=1)
			imageline($img,$v[2][0],$v[2][1],$v[1][0],$v[1][1],$col);
		if ($lat!=2)
			imageline($img,$v[0][0],$v[0][1],$v[2][0],$v[2][1],$col);
	}
	
	public function show() {
		$image = imageCreate($this->w, $this->h); 
		$bg = imageColorAllocate($image, 255, 255, 255); 
		imagesetthickness($image,3);
		switch ($this->type) {
			case 0 :
				$this->draw_circle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_circle($image,rand()%($this->size*0.7));
			break;
			case 1 :
				$this->draw_circle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_circle($image,rand()%((min($this->w,$this->h)/2)-$this->size)+$this->size*1.2);
			break;
			case 2 :
				$this->draw_square($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_square($image,rand()%($this->size*0.7));
			break;
			case 3 :
				$this->draw_square($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_square($image,rand()%((min($this->w,$this->h)/2)-$this->size)+$this->size*1.2);
			break;
			case 4 :
				$this->draw_triangle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_triangle($image,rand()%($this->size*0.7));
			break;
			case 5 :
				$this->draw_triangle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_triangle($image,rand()%((min($this->w,$this->h)/2)-$this->size)+$this->size*1.2);
			break;
			case 16 :
			case 6 :
				$this->draw_square($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					if (rand()%2)
						$this->draw_triangle($image,rand()%(min($this->w,$this->h)/4));
					else
						$this->draw_circle($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 17 :
			case 7 :
				$this->draw_triangle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					if (rand()%2)
						$this->draw_square($image,rand()%(min($this->w,$this->h)/4));
					else
						$this->draw_circle($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 15 :
			case 8 :
				$this->draw_circle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					if (rand()%2)
						$this->draw_triangle($image,rand()%(min($this->w,$this->h)/4));
					else
						$this->draw_square($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 9 :
				$this->draw_circle_open($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_circle($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 10 :
				$this->draw_circle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_circle_open($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 11 :
				$this->draw_square_open($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_square($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 12 :
				$this->draw_square($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_square_open($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 13 :
				$this->draw_triangle_open($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_triangle($image,rand()%(min($this->w,$this->h)/4));
			break;
			case 14 :
				$this->draw_triangle($image,$this->size,$this->center);
				for ($i=0;$i<$this->items;$i++)
					$this->draw_triangle_open($image,rand()%(min($this->w,$this->h)/4));
			break;
		}
		header('Content-type: image/png');
		imagepng($image); 
		imagedestroy($image); 
	}
	
	public function get_img($no_html=false) {
		return '<div class="ale_captcha" style="width:'.$this->w.'px;height:'.$this->h.'px;background:url('.__http_host.__http_path.'/captcha.php?id='.$this->id.')" id="'.$this->id.'"></div>';
	}
	
	public function text($no_html=false) {
		$this->generate();
		include(__base_path.'langs/'.LANG::short().'/captcha.php');
		return (($no_html)?'':'<span class="ale_captcha_t" id="t'.$this->id.'">').$__captcha_texts[$this->type].(($no_html)?'':'</span>');
	}
}
?>