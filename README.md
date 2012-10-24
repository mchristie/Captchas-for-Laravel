Captchas-for-Laravel
====================

Super simple captcha model, generates, validates and clears captchas.

Installation
============

1) Move captcha.php into your models folder.
2) Put this code in your routes.php
```PHP
Route::get('captcha', function() {
	return Captcha::make();
});
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
