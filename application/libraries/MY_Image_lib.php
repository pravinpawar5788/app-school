<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	public function __construct($config = array())
        {
                parent::__construct($config);
				//assign the codeIgniter super-object
				$this->CI =& get_instance();
        }

	//////////////////////////////////////////////////
	//resize image to specific width*height	
	public function resize_image($image_source,$target_path,$width=200,$height=200){
    $config['source_image'] = $image_source;
    $config['new_image'] = $target_path;
    $config['maintain_ratio'] = TRUE;
    $config['width']    = $width;
    $config['height']   = $height;
    $this->initialize($config);
	if (!$this->resize()){return $this->display_errors();}
    $this->clear(); //clear cache  
	return true;
    }
	//rotate image to angle
	public function rotate_image($image_source,$target_path,$angle=90,$width=200,$height=200){
    $config['source_image'] = $image_source;
    $config['new_image'] = $target_path;
    //$config['maintain_ratio'] = TRUE;
    $config['rotation_angle'] = $angle;
    //$config['width']    = $width;
    //$config['height']   = $height;
    $this->initialize($config);
	if (!$this->rotate()){return $this->display_errors();}
    $this->clear(); //clear cache  
	return true;
    }
	//watermark an image
	public function watermark_image($image_source,$target_path,$text,$type='text',$font_size=16,$font_color='#ffffff',$vertical='center',$horizontal='bottom',$padding=20){
    $config['source_image'] = $image_source;
    $config['new_image'] = $target_path;
    $config['wm_text']    = $text;
    $config['wm_type']   = $type;
    //$config['wm_font_path']   ='';
    $config['wm_font_size']   = $font_size;
    $config['wm_font_color']   = $font_color;
    $config['wm_vrt_alignment']   = $vertical;
    $config['wm_hor_alignment']   = $horizontal;
    $config['wm_padding']   = $padding;
    $this->initialize($config);
	if (!$this->watermark()){return $this->display_errors();}
    $this->clear(); //clear cache  
	return true;
    }
	
	
}
