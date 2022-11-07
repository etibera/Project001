<?php
class ImageResizer {
  
//public $http_image = 'http://localhost/peso/';
	public $http_image = 'http://localhost/peso-web-new/'; //for test server
	public $https_image = 'https://pesoapp.ph/';
    public function checkImageUrl($url)
    {
       if (@getimagesize($url)) {
        return  $url;
        } else {
        return  null;
        }
    }
	public function img_path($new_image){
		if (isset($_SERVER['HTTPS'])){
			return $this->https_image . 'img/' . $new_image;
		} else {
			return $this->http_image . 'img/' . $new_image;
		}
	}
	public function dir_image(){
			//return isset($_SERVER['HTTPS']) ? '/home/irpge67jnamu/public_html/img/' : 'c:/xampp/htdocs/peso-web-new/img/';
		return isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/' : '/var/www/html/peso-web-new/img/';
	}
    public function resize($filename, $width, $height, $anotherPath = 'cache/'){
        if (!is_file($this->dir_image() . $filename)) {
			return;
        }
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $old_image = $filename;
		$new_image = $anotherPath . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file($this->dir_image() . $new_image) || (filectime($this->dir_image() . $old_image) > filectime($this->dir_image() . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir($this->dir_image() . $path)) {
					@mkdir($this->dir_image() . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = @getimagesize($this->dir_image() . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image($this->dir_image() . $old_image);
				$image->resize($width, $height);
				$image->save($this->dir_image() . $new_image);
			} else {
				copy($this->dir_image() . $old_image, $this->dir_image() . $new_image);
			}
		}

		if (isset($_SERVER['HTTPS'])){
			return $this->https_image . 'img/' . $new_image;
		} else {
			return $this->http_image . 'img/' . $new_image;
		}
        
    }
}
$image = new ImageResizer();