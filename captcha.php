<?

/*
*	Laravel support added by Malcolm Christie 2012
*	malcolmchristie.co.uk
*
*	Much of this shamelessly taken from Simon Jarvis's code released under the GNU license
*	http://www.white-hat-web-design.co.uk/articles/php-captcha.php
*
*/

class Captcha extends Eloquent {

	public static $table = 'captchas';

	public static function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	public static function make($width = 120, $height = 40, $characters = 6, $max_rotation = 25) {
		$code = self::generateCode($characters);
		$font = path('storage').'monofont.ttf';

		$save = new Captcha();
		$save->code = $code;
		$save->save();

		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		/* set the colours */
		$background_color = imagecolorallocate($image, 244,244,244);
		$text_color = imagecolorallocate($image, 84, 10, 10);
		$noise_color = imagecolorallocate($image, 189, 56, 56);

		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}

		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}

		/* create textbox and add text */
		$letter_width = ($width - 10) / $characters;
		$x_offset = 5;
		for ($i = 0; $i < $characters; $i++) {
			$letter = substr($code, $i, 1);
			$textbox = imagettfbbox($font_size, 0, $font, $letter) or die('Error in imagettfbbox function');
			$x = $x_offset + ($letter_width - $textbox[4])/2;
			$y = ($height - $textbox[5])/2;
			$angle = rand($max_rotation * -1, $max_rotation);
			imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font , $letter) or die('Error in imagettftext function');
			$x_offset += $letter_width;
		}

		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}

	public static function check($code) {
		$captcha = Captcha::where('code', '=', $code)->first();
		return ($captcha && $captcha->exists) ? true : false;
	}

	public static function clear($days = 1) {
		Captcha::where('created_at', '<', date('c', strtotime("- $days days")))->delete();
	}
}
