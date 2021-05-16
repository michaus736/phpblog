<?php 

class Captcha{
	
	public $z1;
	public $calosc;
	public $wielkosc;
	public $czcionka;
	public $obrot;
	public $newczcionka;
	
	public function __construct()
	{
		$iterator=0;
		foreach (new DirectoryIterator('fonts/') as $fileInfo) 
		{
			if($fileInfo->getFilename()=="index.php")
			{
				
			}
			else
			{
				$this->czcionka[$iterator]=$fileInfo->getFilename();
				$iterator++;
			}
		}
		$this->z1=range('A','Z');
		shuffle($this->z1);
		
		if(isset($_SESSION['captcha']))
		{
			$_SESSION['zcaptcha']=$_SESSION['captcha'];
			
		}
		$_SESSION['captcha']=$this->kod();
		
		
		for($i=0; $i<4; $i++)
		{
			$this->wielkosc[$i]=rand(25,40);
			$this->obrot[$i]=rand(-50,50);
		}
			$this->img();
		
	}
	public function kod()
	{
		$kod="";
		for($i=0; $i<4; $i++)
		{
			$kod.=$this->z1[$i];
		}
		return $kod;
	}/////tu jeszcze działa
	public function img()
	{
		$im = imagecreatetruecolor(250, 100);
		$text_color = imagecolorallocate($im, 0, 14, 91);
		imagealphablending($im,false);
		$tran = imagecolorallocatealpha($im,0,0,0,127);
		
		imagefill($im,0,0,$tran);
		imagesavealpha($im, true);
		$x=40;
		for($i=0; $i<4; $i++)
		{
			$xx=$x+$i*40;
			$this->newczcionka[$i]= "fonts/".$this->czcionka[random_int(2,count($this->czcionka)-1)];
			imagettftext($im,$this->wielkosc[$i],$this->obrot[$i],$xx,60,$text_color,$this->newczcionka[$i],$this->z1[$i]);
		}
		imagepng($im,'kod/kod.png');
		imagedestroy($im);

	}
}
?>