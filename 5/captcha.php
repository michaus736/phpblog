<?php class Captcha{
    public $letterkey;
    public $lettercount;
    public $fonts;
    public $filepath;
    public $imgx;
    public $imgy;
    public $img;
    public $file;
    public function __destruct()
    {
        fclose($this->file);
        //unlink($this->filepath);
    }
    public function __construct()
    {
        $this->letterkey=array();
        $this->lettercount=4;
        $this->fonts=array(
            "arial.ttf",
            "hemihead.ttf",
            "leadcoat.ttf",
            "stocky.ttf",
            "stormfaze.ttf"
            ,);
        $this->filepath="./files/".uniqid().".png";
        $this->imgx=650;
        $this->imgy=350;
        $this->img=imagecreatetruecolor($this->imgx,$this->imgy);
   
        $white=imagecolorallocate($this->img,255,255,255);
        for($i=0;$i<$this->lettercount;$i++){
            $actualfont="./fonts/".$this->fonts[array_rand($this->fonts)];
            
            $letter = chr(rand(65,90));
            array_push($this->letterkey,$letter);
            $angle = rand(-50,50);
            $fontsize= rand(14,48);
            imagettftext($this->img, $fontsize, $angle, $i + $i * $fontsize + 50 * $i, $this->imgy/2 + rand(-20,20), $white, __DIR__.$actualfont, $letter);
         }
        $this->file=fopen($this->filepath,"w");
        imagepng($this->img,$this->file);
    }

}


?>