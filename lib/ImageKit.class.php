<?php
define('IMG_WIDTH', 0);
define('IMG_HEIGHT', 1);
define('IMG_TYPE', 2);

class ImageKit {
	private $_is_gb_available;
	
	protected $fpath;
	protected $res;
	protected $img_info;
	protected $dst_im;
	
	function __construct($fpath) {
		$this->_is_gb_available = (extension_loaded('gd') || dl('gd'));
		$this->fpath = $fpath;
	}
	
	public function load($strict = false)
	{
		if (!$this->_is_gb_available) {
			return false;
		}
		
		if (is_resource($this->res) && !$strict) {
			return $this->res;
		}
		
		$this->res = null;
		
		$this->img_info = getimagesize($this->fpath);

//		list($this->_width, $this->_height, $imgtype, $imginfo, $bits, $channels, $mime) = $info;
//		0 => 1221
//		1 => 849
//		2 => 2
//		3 => width="1221" height="849"
//		bits => 8
//		channels => 3
//		mime => image/jpeg

		switch ($this->img_info[IMG_TYPE])
		{
			case 3:
//				trace("IMG_PNG");
				$this->res = imagecreatefrompng($this->fpath);
				break;
			
			case 2:
//				trace("IMG_JPG");
				$this->res = imagecreatefromjpeg($this->fpath);
				break;
			
			case 1:
//				trace("IMG_GIF");
				$this->res = imagecreatefromgif($this->fpath);
				break;
				
			default:
//				trace("OTHER IMG, try to read from string");
				$imgstr = file_get_contents($this->fpath);
				$this->res = @imagecreatefromstring($imgstr);
		}
		
		return $this->res;
	}
	
	public function convert($width = null, $height = null)
	{
		if ( is_numeric($width) && is_numeric($height)  ) {
			return $this->_convert_both($width, $height);
		} elseif ( is_numeric($width) ) {
			return $this->_convert_by_width($width);
		} elseif ( is_numeric($height) ) {
			return $this->_convert_by_height($height);
		} else {
			return null;
		}
	}
	
	function _convert_by_width($width)
	{
//		trace("convert width: $width");
		
		if ( $width >= $this->img_info[IMG_WIDTH] ) {
			return $this->res;
		}
		
		$k = $width / $this->img_info[IMG_WIDTH];
		
		$cutWidth = $width;
		$cutHeight = $k * $this->img_info[IMG_HEIGHT];
		
//		trace("cut: $cutWidth, $cutHeight");
		
		$retImg = $this->_convert_img(0, 0, $this->img_info[IMG_WIDTH], $this->img_info[IMG_HEIGHT], $cutWidth, $cutHeight);
		
		return $retImg;
	}
	
	function _convert_by_height($height)
	{
		if ( $height >= $this->img_info[IMG_HEIGHT] ) {
			return $this->res;
		}
		
		$k = $height / $this->img_info[IMG_HEIGHT];
		
		$cutHeight = $height;
		$cutWidth = $k * $this->img_info[IMG_WIDTH];
		
		$retImg = $this->_convert_img(0, 0, $this->img_info[IMG_WIDTH], $this->img_info[IMG_HEIGHT], $cutWidth, $cutHeight);
		
//		trace($retImg ? "convert succeed." : "convert failed.");
		
		return $retImg;
	}
	
	function _convert_both($width, $height)
	{
		$src_ratio = $this->img_info[IMG_WIDTH] / $this->img_info[IMG_HEIGHT];
		$dst_ratio = $width / $height;
		
		if ($src_ratio < $dst_ratio) {
			$src_w = $this->img_info[IMG_WIDTH];
			$src_h = $this->img_info[IMG_WIDTH] / $dst_ratio;

			$src_y = ( $this->img_info[IMG_HEIGHT] - $src_h ) / 2;
			$src_x = 0;
		} else {
			$src_w = $dst_ratio * $this->img_info[IMG_HEIGHT];
			$src_h = $this->img_info[IMG_HEIGHT];

			$src_y = 0;
			$src_x = ( $this->img_info[IMG_WIDTH] - $src_w ) / 2;
		}
		
		$convImg = $this->_convert_img($src_x, $src_y, $src_w, $src_h, $width, $height);
		
//		trace($convImg ? "convert succeed." : "convert failed.");
		
		return $convImg;
	}
	
	function _convert_img($src_x, $src_y, $src_w, $src_h, $dst_width, $dst_height)
	{
		$dst_im = imagecreatetruecolor($dst_width, $dst_height);
		
		$ret = imagecopyresampled($dst_im, $this->res,
						 0, 0, $src_x, $src_y,
						 $dst_width, $dst_height,
						 $src_w, $src_h
						);
//		trace("convert image: $src_x, $src_y, $src_w, $src_h, $dst_width, $dst_height");
		
		if ( !$ret ) {
//			trace("copy image failed.");
		}
		
		$this->dst_im = $dst_im;
		
		return $this->dst_im;
	}

	public function unload() {
		if ( is_resource($this->res) ) {
			imagedestroy($this->res);
		}
	}
	
	function output($filename, $quality = 80, $destory = true)
	{
		if (!is_resource($this->res)) {
//			trace("image is null, failed.");
			return false;
		}

		$res = is_resource($this->dst_im) ? ($this->dst_im) : ($this->res);
		$ret = imagejpeg($res, $filename, $quality);

		if ( $destory && is_resource($this->res) ) {
			$this->unload();
		}
		
		return $ret;
	}
	
	function __destruct() {
		$this->unload();
	}
}