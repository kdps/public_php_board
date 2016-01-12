<?php

class Func
{

    public function __construct()
    {
		
    }
	
	function getPictureWidth($file)
	{
		$imageInfo = getimagesize($file);
		if ($imageInfo['mime'] == ("image/png"))
		{
			$img = imagecreatefromjpeg($file);
			$width = imagesx($img);
		}
		elseif($imageInfo['mime'] == ("image/jpeg"))
		{
			$img = imagecreatefromjpeg($file);
			$width = imagesx($img);
		}
		elseif($imageInfo['mime'] == ("image/png"))
		{
			$img = imagecreatefrompng($file);
			$width = imagesx($img);
		}
		
		return $width;
	}
	
	public static function round_up($value, $places) 
	{
		$mult = pow(10, abs($places)); 
		 return $places < 0 ?
		ceil($value / $mult) * $mult :
			ceil($value * $mult) / $mult;
	}

	function sendMail($to, $subject, $message)
	{
		mail($to, $subject, $message);
	}
	
	function makeDir($target, $mode)
	{
		if(!is_dir($target))
		{
			mkdir($target,$mode);
		}
	}
	
	function getUrl($args,$short_url)
	{
		$return_url = NULL;
		$func_num = func_num_args();
		$func_get = func_get_args();
		
		$i=0;
		while($i<$func_num)
		{
			if($return_url)
			{
				if($func_get[$i+1])
				{
					$return_url .= '&'.$func_get[$i].'='.$func_get[$i+1];
				}
			}
			else
			{
				$return_url .= 'index.php?';
				$return_url .= $func_get[$i].'='.$func_get[$i+1];
			}
			$i = $i+2;
		}
		
		return $return_url;
	}
	
	//get filesize
	function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}
		elseif ($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}
		elseif ($bytes >= 1024)
		{
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}
		elseif ($bytes > 1)
		{
			$bytes = $bytes . ' bytes';
		}
		elseif ($bytes == 1)
		{
			$bytes = $bytes . ' byte';
		}
		else
		{
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	//utf-8
	function filter_string($data,$encoding='UTF-8')
	{
	   return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
	}
	
	//minify
	public function zip_output()
	{
		function sanitize_output($buffer) {

			$search = array(
				'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
				'/[^\S ]+\</s',  // strip whitespaces before tags, except space
				'/(\s)+/s'       // shorten multiple whitespace sequences
			);

			$replace = array(
				'>',
				'<',
				'\\1'
			);

			$buffer = preg_replace($search, $replace, $buffer);

			return $buffer;
		}

		ob_start("sanitize_output");
	}
	
	
	public function createThumbs($pathToImages, $pathToThumbs, $thumbWidth, $thumbHeight) 
	{
		
		$info = pathinfo($pathToImages);
		
		if(strtolower($info['extension']) == 'jpg')
		{
			$img = imagecreatefromjpeg($pathToImages);
			$width = imagesx($img);
			$height = imagesy($img);

			$new_width = $thumbWidth;
			
			if($thumbHeight=="0")
			{
				$new_height = floor( $height * ( $thumbWidth / $width ) );
			}
			else
			{
				$new_height = $thumbHeight;
			}
			
			$tmp_img = imagecreatetruecolor($new_width, $new_height);

			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagejpeg($tmp_img, $pathToThumbs);
		}
		
		if(strtolower($info['extension']) == 'png')
		{
			$img = ImageCreateFromPNG($pathToImages);
			$width = imagesx($img);
			$height = imagesy($img);

			$new_width = $thumbWidth;
			
			if($thumbHeight=="0")
			{
				$new_height = floor( $height * ( $thumbWidth / $width ) );
			}
			else
			{
				$new_height = $thumbHeight;
			}
			
			$tmp_img = imagecreatetruecolor($new_width, $new_height);

			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			ImagePng($tmp_img, $pathToThumbs);
		}
	}
}
