<?php
	session_start();
	$iwidth = isset($_GET['width']) ? $_GET['width'] : '120';
	$iheight = isset($_GET['height']) ? $_GET['height'] : '40';
	$scharacters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';
	$captcha = generateimage($iwidth,$iheight,$scharacters);

	function generateimage($iwidth='120',$iheight='40',$scharacters='6')
	{
		require_once('/lib/sessions.class.php');

		$sfontname = getcwd().'/css/monofont.ttf';

		/* list all possible characters, similar looking characters and vowels have been removed */
		$spossiblechars = '0123456789AbcDeFgHiJkLmNoPqRsTUvwxyZ';
		$ssecuritycode = '';
		$i = 0;
		while ($i < $scharacters)
		{
			$ssecuritycode .= substr($spossiblechars, mt_rand(0, strlen($spossiblechars)-1), 1);
			$i++;
		}

		/* font size will be 75% of the image height */
		$sfont_size = $iheight * 0.75;
		$simagename = @imagecreate($iwidth, $iheight) or die('Cannot initialize new GD image stream');

		/* set the colours */
		$sbackground_color 	= imagecolorallocate($simagename, 240,230,140);
		$stext_color 		= imagecolorallocate($simagename, 255, 215, 0);
		$snoise_color 		= imagecolorallocate($simagename, 139,69,19);

		/* generate random dots in background */
		for( $i=0; $i<($iwidth*$iheight)/3; $i++ )
		{
			imagefilledellipse($simagename, mt_rand(1,$iwidth), mt_rand(1,$iheight), 4, 0, $snoise_color);
		}

		/* generate random lines in background */
		for( $i=0; $i<($iwidth*$iheight)/150; $i++ )
		{
			imageline($simagename, mt_rand(1,$iwidth), mt_rand(1,$iheight), mt_rand(1,$iwidth), mt_rand(1,$iheight), $snoise_color);
		}

		/* create textbox and add text */
		$stextbox = imagettfbbox($sfont_size, 0, $sfontname, $ssecuritycode) or die('Error in imagettfbbox function');
		$dx = ($iwidth - $stextbox[4])/2;
		$dy = ($iheight - $stextbox[5])/2;

		imagettftext($simagename, $sfont_size, 0, $dx, $dy, $stext_color, $sfontname , $ssecuritycode) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($simagename);
		imagedestroy($simagename);
		Sessions::setSecurityCode($ssecuritycode);
}

?>