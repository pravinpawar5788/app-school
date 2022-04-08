<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailtemp{
		
	
	protected $CI;
	protected $title;
	protected $site_url;
	protected $site_name;
	protected $site_address;
	protected $custom_style;
	protected $logo_url;
	protected $tag_line;
	protected $from_footer_text;
	protected $bg_color;
	
	
	//constructor of the class, optional params will loading the class
	function __construct(){
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		$this->CI->config->load('emailtemp',true);
		$this->CI->load->helper('url');
		$this->initialize();
	}
	
	public function initialize(){
		$config=$this->CI->config->item('emailtemp');
		//assign default values to variables
		$this->custom_style='';
		if(array_key_exists('et_title', $config)){			
			empty($config['et_title'])? $this->title=$this->getDomain(base_url()) : $this->title=$config['et_title'];
		}else{$this->title=$this->getDomain(base_url());}
		
		if(array_key_exists('et_site_url', $config)){			
			empty($config['et_site_url'])? $this->site_url=base_url() : $this->site_url=$config['et_site_url'];
		}else{$this->site_url=base_url();}
		
		if(array_key_exists('et_site_name', $config)){			
			empty($config['et_site_name'])? $this->site_name=$this->getDomain(base_url()) : $this->site_name=$config['et_site_name'];
		}else{$this->site_name=$this->getDomain(base_url());}
		
		if(array_key_exists('et_address', $config)){			
			empty($config['et_address'])? $this->site_address='' : $this->site_address=$config['et_address'];
		}else{$this->site_address='';}
		
		if(array_key_exists('et_logo_url', $config)){			
			empty($config['et_logo_url'])? $this->logo_url='' : $this->logo_url=$config['et_logo_url'];
		}else{$this->logo_url='';}
		
		if(array_key_exists('et_tag_line', $config)){			
			empty($config['et_tag_line'])? $this->tag_line='' : $this->tag_line=$config['et_tag_line'];
		}else{$this->tag_line='';}
		
		if(array_key_exists('et_from_footer_text', $config)){			
			empty($config['et_from_footer_text'])? $this->from_footer_text='Support Team' : $this->from_footer_text=$config['et_from_footer_text'];
		}else{$this->from_footer_text='Support Team';}
		
		////////////
		$this->bg_color=array('#5D9CEC','#48CFAD','#F6BB42','#4FC1E9','#1A1423','#42213D','#F51AA4','#0B132B',
		'#EF233C','#D80032','#2D936C','#59114D','#570000','#07393C','#2C666E','#149911','#256D1B',
		'#244F26','#42033D','#680E4B','#7C72A0','#7C238C');
		
		
		
	}
	
	//get domain from url
	private function getDomain($url){
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
        return $regs['domain'];
    }
    return $domain;
	}
	
	
	//style provider
	private function provide_style(){
		$style='<style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class="wrapper"]{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class="logo"]{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class="logo"] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class="mobile-hide"]{
          display:none;}

        img[class="mobile-hide"]{
          display: none !important;
        }

        img[class="img-max"]{
          max-width: 100% !important;
          height:auto !important;
        }

        /* FULL-WIDTH TABLES */
        table[class="responsive-table"]{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class="padding"]{
          padding: 10px 5% 15px 5% !important;
        }

        td[class="padding-copy"]{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class="padding-meta"]{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class="no-pad"]{
          padding: 0 0 20px 0 !important;
        }

        td[class="no-padding"]{
          padding: 0 !important;
        }

        td[class="section-padding"]{
          padding: 50px 15px 50px 15px !important;
        }

        td[class="section-padding-bottom-image"]{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class="mobile-wrapper"]{
            padding: 10px 5% 15px 5% !important;
        }

        table[class="mobile-button-container"]{
            margin:0 auto;
            width:100% !important;
        }

        a[class="mobile-button"]{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
	'.$this->custom_style.'
	</style>';
	return $style;
	}
	//provide header of html page
	private function provide_header(){
		
		$private_style=$this->provide_style();
		$header='<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>'.$this->title.'</title>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width">'.
			$private_style.'</head>
			<body style="margin: 0; padding: 0;">';
		return $header;
	}
	
	//provide footer block
	private function provide_block_footer($data=array()){
	$block='<!-- FOOTER -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td style="padding: 20px 0px 20px 0px;">
                        <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                            <tr>
                                <td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                                    <span class="appleFooter" style="color:#666666;">'.$this->site_address.'</span><br>
									<a class="original-only" style="color: #666666; text-decoration: none;">'.$this->from_footer_text.' </a>
									<span class="original-only" style="font-family: Arial, sans-serif; font-size: 12px; color: #444444;">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
									<a href="'.$this->site_url.'" class="original-only" style="color: #666666; text-decoration: none;">'.$this->site_name.'</a> 
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	</table>
	</body>
	</html>';
	return $block;
	}
	
	//provide header block
	private function provide_block_header($data=array()){
	$block='';
	if(!empty($this->logo_url) && !empty($this->tag_line)){
	$block='<!-- MAIN HEADER -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff">
            <div align="center" style="padding: 0px 15px 0px 15px;">
                <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style="padding: 20px 0px 30px 0px;" class="logo">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td bgcolor="#ffffff" width="100" align="left"><a href="'.$this->site_url.'" target="_blank"><img alt="Logo" src="'.$this->logo_url.'" width="52" height="78" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a></td>
                                    <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;">'.$this->tag_line.'</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
	</table>';
	}
	return $block;
	}
	
	//provide article header
	private function provide_article_header($data=array()){
		array_key_exists('article_title', $data)? $article_title=$data['article_title'] : $article_title='';
		
	$block='';	
	$block='<!-- COMPACT ARTICLE SECTION -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#f8f8f8" align="center" style="padding: 70px 15px 70px 15px;" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" style="padding:0 0 20px 0;" class="responsive-table">
                <!-- TITLE -->
                <tr>
                    <td align="left" style="padding: 0 0 10px 130px; font-size: 25px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333333;" class="padding-copy" colspan="2">'.$article_title.'</td>
                </tr>';
	return $block;
	}
	//provide article footer
	private function provide_article_footer($data=array()){
		//array_key_exists('article_title', $data)? $article_title=$data['article_title'] : $article_title='';
		
	$block='';	
	$block='</table>
        </td>
    </tr>
</table>';
	return $block;
	}
	//provide article block
	private function provide_block_article($data=array()){
		array_key_exists('image_url', $data)? $image_url=$data['image_url'] : $image_url='';	
		array_key_exists('image_link', $data)? $image_link=$data['image_link'] : $image_link='#';	
		array_key_exists('btn_text', $data)? $btn_text=$data['btn_text'] : $btn_text='See More ';	
		array_key_exists('btn_url', $data)? $btn_url=$data['btn_url'] : $btn_url='';	
		array_key_exists('block_title', $data)? $block_title=$data['block_title'] : $block_title='';
		//array_key_exists('block_sub_title', $data)? $block_sub_title=$data['block_sub_title'] : $block_sub_title='';
		array_key_exists('block_text', $data)? $block_text=$data['block_text'] : $block_text='';
	$block='';
	$image_block='';
	$btn_block='';
	
	if(!empty($btn_url)){
		$bg_color=$this->bg_color[array_rand($this->bg_color)];
		$btn_block='<tr>
                                <td>
                                    <!-- BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                    <tr>
                                                        <td align="center"><a href="'.$btn_url.'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: '.$bg_color.'; border-top: 15px solid '.$bg_color.'; border-bottom: 15px solid '.$bg_color.'; border-left: 25px solid '.$bg_color.'; border-right: 25px solid '.$bg_color.'; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">'.$btn_text.' &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
	}
	if(!empty($image_url)){
		$image_block='<td valign="top" style="padding: 40px 0 0 0;" class="mobile-hide">
			<a href="'.$image_link.'" target="_blank">
				<img src="'.$image_url.'" alt="" width="105" height="105" border="0" style="display: block; font-family: Arial; color: #666666; font-size: 14px; width: 105px; height: 105px;">
			</a></td>';
	}
	
	$block='<tr>'.$image_block.'
            <td style="padding: 40px 0 0 0;" class="no-padding">
						<!-- ARTICLE -->
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            
                            <tr>
                                <td align="left" style="padding: 0 0 5px 25px; font-size: 22px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333333;" class="padding-copy">'.$block_title.'</td>
                            </tr>
                            <tr>
                                 <td align="left" style="padding: 10px 0 15px 25px; font-size: 16px; line-height: 24px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'.$block_text.'</td>
                            </tr>
                            <tr>
                                <td style="padding:0 0 45px 25px;" align="left" class="padding">
                                    <table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center">
                                                '.$btn_block.'
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';
	return $block;
	}
	//provide top image block
	private function provide_block_simple($data=array()){
		array_key_exists('image_url', $data)? $image_url=$data['image_url'] : $image_url='';	
		array_key_exists('image_link', $data)? $image_link=$data['image_link'] : $image_link='#';	
		array_key_exists('btn_text', $data)? $btn_text=$data['btn_text'] : $btn_text='See More ';	
		array_key_exists('btn_url', $data)? $btn_url=$data['btn_url'] : $btn_url='';	
		array_key_exists('block_title', $data)? $block_title=$data['block_title'] : $block_title='';
		array_key_exists('block_text', $data)? $block_text=$data['block_text'] : $block_text='';
	$block='';
	$image_block='';
	$btn_block='';
	
	if(!empty($image_url)){
		$image_block='<tr>
                                <td>
                                    <!-- HERO IMAGE -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                             <tr>
                                                  <td class="padding-copy">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                              <td>
                                                                  <a href="'.$image_link.'" target="_blank"><img src="'.$image_url.'" width="500" height="200" border="0" alt="" style="display: block; padding: 0; color: #666666; text-decoration: none; font-family: Helvetica, arial, sans-serif; font-size: 16px; width: 500px; height: 200px;" class="img-max"></a>
                                                              </td>
                                                            </tr>
                                                        </table>
                                                  </td>
                                              </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
	}
	if(!empty($btn_url)){
		$bg_color=$this->bg_color[array_rand($this->bg_color)];
		$btn_block='<tr>
                                <td>
                                    <!-- BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                    <tr>
                                                        <td align="center"><a href="'.$btn_url.'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: '.$bg_color.'; border-top: 15px solid '.$bg_color.'; border-bottom: 15px solid '.$bg_color.'; border-left: 25px solid '.$bg_color.'; border-right: 25px solid '.$bg_color.'; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">'.$btn_text.' &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
	}
	
	$block='<!-- ONE COLUMN IMAGE ON TOP SECTION -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 70px 15px 70px 15px;" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            '.$image_block.'
                            <tr>
                                <td>
                                    <!-- Text -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy">'.$block_title.'</td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'.$block_text.'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            '.$btn_block.'
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	</table>';
	return $block;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////render html////////////////////////////////////////////////////
	public function render_html($data=array()){		
		array_key_exists('title', $data)? $this->title=$data['title'] : '';		
		array_key_exists('site_name', $data)? $this->site_name=$data['site_name'] : '';		
		array_key_exists('from_footer_text', $data)? $this->from_footer_text=$data['from_footer_text'] : '';	
		array_key_exists('site_url', $data)? $this->site_url=$data['site_url'] : '';
		array_key_exists('address', $data)? $this->site_address=$data['address'] : '';
		array_key_exists('style', $data)? $this->style=$data['style'] : '';
		array_key_exists('blocks', $data)? $blocks=$data['blocks'] : $blocks=array();
		
		//print_r($data);
		//////////////////////////////////////////
		$page='';
		$page.=$this->provide_header($data);
		$page.=$this->provide_block_header($data);
		//////////////////////////////////////////
		foreach($blocks as $block){
			if($block['type']=='block'){
				$page.=$this->provide_block_simple($block);
			}
		}
		$i=0;
		foreach($blocks as $block){			
			if($block['type']=='article'){	
				$i++;
				if($i==1){$page.=$this->provide_article_header($block);}
				$page.=$this->provide_block_article($block);
				if($i==1){$page.=$this->provide_article_footer($block);}	
			}
		}
		///////////////////////////////////////////
		$page.=$this->provide_block_footer($data);
		return $page;
		
	}
	
}
