Captchas for Laravel
====================

Super simple captcha model - generates, validates and clears Captchas. This is a very simple implementation that integrates very easily with existing form validation.

The captcha files are never stored, so tidying up is very minimal, simply run the ``Captcha::clear()`` function whenever the table gets too full.

Installation
============

1) Move captcha.php into your models folder.

2) Put this code in your routes.php
```PHP
Route::get('captcha', function() {
	return Captcha::make();
});
```
If you need to, you can customize the image using these parameters
```PHP
return Captcha::make( $width = 120, $height = 40, $characters = 6, $max_rotation = 25 );
```

3) Create the captcha table with this
```MySQL
CREATE TABLE `<database_name>`.`captchas` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`code` varchar(20) NOT NULL,
	`updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT '0000-00-00 00:00:00',
	`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
) ENGINE=`InnoDB` AUTO_INCREMENT=51 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ROW_FORMAT=COMPACT CHECKSUM=0 DELAY_KEY_WRITE=0;
```

Generating a captcha
--------------------
```HTML
<img src="/captcha">
<input type="text" name="captcha">
```

Validating a captcha
--------------------
```PHP
if (Captcha::check(Input::get('captcha'))) {
	// Correct!
} else {
	// Wrong :(
}
```

Clearing old captchas
---------------------
```PHP
Captcha::clear( $days_old = 1)
```

Changing colours (optional)
----------------
You can change these values in captchas.php to easily modify the colours to blend in with your design nicely
```PHP
// Line 41
$background_color = imagecolorallocate($image, 244,244,244);
$text_color = imagecolorallocate($image, 84, 10, 10);
$noise_color = imagecolorallocate($image, 189, 56, 56);
```
