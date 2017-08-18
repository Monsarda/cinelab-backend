<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Captcha extends Cinelab
{

	public function __construct()
	{

	}

	public function get_captcha()
	{
		$resolveSymbol = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvWwXxYyZz123456789';

		//получает первые 6 символов после их перемешивания с помощью функции str_shuffle
		$captchastring = substr(str_shuffle($resolveSymbol), 0, 6);
		//инициализация переменной сессии с помощью сгенерированной подстроки captchastring,
		//содержащей 6 символов
		$_SESSION['captcha'] = $captchastring;

		//Генерирует CAPTCHA
		$image = imagecreatetruecolor(120, 50);

		imagesavealpha($image, false); //устанавливает прозрачность изображения

		$bg = imagecolorallocatealpha($image, 255, 255, 255, 0); //идентификатор цвета для изображения

		imagefill($image, 0, 0, $bg); //выполняет заливку цветом

		//устанавливает цвет (R-200, G-240, B-240) изображению, хранящемуся в $image
		$colour = imagecolorallocate($image, 0, 0, 0);

		//присваивает переменной font название шрифта
		$font = $_SERVER['DOCUMENT_ROOT'].'/templates/fonts/oswald.ttf';

		//устанавливает случайное число между -10 и 10 градусов для поворота текста 
		$rotate = rand(-10, 10);

		// рисует текст на изображении шрифтом TrueType 
		// 1 параметр - изображение ($image), 
		// 2 - размер шрифта (18), 
		// 3 - угол поворота текста ($rotate), 
		// 4, 5 - начальные координаты x и y для текста (18,30), 
		// 6 - индекс цвета ($colour),
		// 7 - путь к файлу шрифта ($font), 
		// 8 - текст ($captchastring) 
		imagettftext($image, 18, $rotate, 28, 32, $colour, $font, $captchastring);
		header('Content-type: image/jpeg');
		imagejpeg($image);	
		imagedestroy($image);
 
	}
}