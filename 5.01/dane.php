<?php 
class Dane
{
	public $tablica;
	public function __construct($nazwa)
	{
		$fp=fopen($nazwa,"r");
		$i=0;
		while($dane=fgetcsv($fp))
		{
			$tablica[$i]=$dane;
			$i+=1;
		}
	}
	
}
?>