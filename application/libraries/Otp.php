<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Otp{
		
	const DEFAULT_HASH = "sha1";
    //Default Expiration is 600 Sec
	const DEFAULT_EXPIRATION = 600;
    //Default length of generated code
	const DEFAULT_DIGITSNR = 7;    
	protected $doubleDigits = array (0 => "0", 1 => "2", 2 => "4", 3 => "6", 4 => "8", 5 => "1", 6 => "3", 7 => "5", 8 => "7", 9 => "9" );
	protected $secretKey;
	protected $time;
	protected $expiration;
	protected $codeDigitsNr;
	protected $addChecksum = false;
	protected $generatedCode;
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	function __construct($secretKey = false, $expiration = false, $digits = false)
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		$this->time = time();
		$this->setSecretKey($secretKey);
		$this->setExpirationTime($expiration);
		$this->setDigitsNumber($digits);
		//$this->CI->load->helper('url');
	}

	
	//Generate a serial no by mask E.g:-
    // $template        = xxx-XXX-999-xxx
    // serail no may be = bbb-DDD-123-jha
    public function GetMaskedSerial($template)
    {
        $k = strlen($template);
        $sernum = '';
        for ($i=0; $i<$k; $i++)
        {
            switch($template[$i])
            {
                case 'X': $sernum .= chr(rand(65,90)); break;
                case 'x': $sernum .= chr(rand(97,122)); break;
                case '9': $sernum .= rand(0,9); break;
                case '-': $sernum .= '-';  break;
                case '_':
                    $r = rand(1,2);
                    if($r == 1){
                    $sernum .= chr(rand(65,90));
                    }elseif($r == 2)
                    {
                        $sernum .= rand(0,9);

                    } else{$sernum .= chr(rand(97,122));} break;

                default: $sernum.=$template[$i];break;
            }
        }
        return $sernum;
    }
    
    //generate a random key of length 16
    public function getRandomKey($length=16)
    {
        $key = '';
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));

        $inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

        for($i=0; $i<$length; $i++)
        {
            $key .= $inputs{mt_rand(0,61)};
        }
        return $key;
    }
    
	/**
	 * Returns the timestamp used for creating the code
	 *
	 */
	public function getTimeUsedInGeneration () {
		return $this->time;
	}
	
	public function getGeneratedCode () {
		return $this->generatedCode;
	}
	
	public function setSecretKey ($secretKey) {
		if ($secretKey) {
			$this->secretKey = $secretKey;
			return true; 
		} else {
			return false;
		}
	}
	
	public function setExpirationTime ($expiration = self::DEFAULT_EXPIRATION) {
		$expiration = (int)$expiration;
		if ($expiration > 0) {
			$this->expiration = $expiration;
			return true;
		} else {
			$this->expiration = self::DEFAULT_EXPIRATION;
			return false;
		}
	}
	
	public function getExpirationTime () {
		return $this->expiration;
	}
	
	public function setDigitsNumber ($digits = self::DEFAULT_DIGITSNR) {
		$digits = (int)$digits;
		if ($digits > 0 && $digits <= count($this->doubleDigits)) {
//		if ($digits > 0) {
			$this->codeDigitsNr = $digits;
			return true;
		} else {
			$this->codeDigitsNr = self::DEFAULT_DIGITSNR;
			return false;
		}
	}

	public function addChecksum ($addChecksum = false) {
		$this->addChecksum = (bool)$addChecksum;
	}
	
	public function calcChecksum($num, $digits) {
		$doubleDigit = true;
		$total = 0;
		while ($digits-- > 0) {
			$digit = (int)($num%10);
			$num /= 10;
			if ($doubleDigit) {
				$digit = $this->doubleDigits[$digit];
			}
			$total += $digit;
			$doubleDigit = !$doubleDigit;
		}
		return 10 - $total%10;
	}
	
	private function hmac($data, $hashFunct = self::DEFAULT_HASH, $rawOutput = true) {
		
		if (!in_array($hashFunct, hash_algos())) {
			$hashFunct = self::DEFAULT_HASH;
		}
		return hash_hmac($hashFunct, $data, $this->secretKey, $rawOutput);
	}
	
	/**
	 * Calculate the one time password
	 *
	 * @param int $movingFactor
	 * @return string
	 */
	protected function calcOTP($movingFactor) {
		
		$movingFactor = floor($movingFactor);
		$digits = $this->addChecksum ? ($this->codeDigitsNr + 1) : $this->codeDigitsNr;
		
		$text = array();
		for($i = 7; $i >= 0; $i--) {
			$text[] = ($movingFactor & 0xff);
			$movingFactor >>= 8;
		}
		$text = array_reverse($text);
		foreach ($text as $index=>$value) {
			$text[$index] = chr($value);
		}
		$text = implode("", $text);
		
		$hash = $this->hmac($text);
		$hashLenght = strlen($hash);
		$offset = ord($hash[$hashLenght-1]) & 0xf;
		
		$hash = str_split($hash);
		foreach ($hash as $index=>$value) {
			$hash[$index] = ord($value);
		}
		
		$binary = ( ($hash[$offset] & 0x7f) << 24) | (($hash[$offset + 1] & 0xff) << 16) | (($hash[$offset + 2] & 0xff) << 8) | ($hash[$offset + 3] & 0xff);
		
		$otp = $binary % pow(10, $this->codeDigitsNr);
		if ($this->addChecksum) {
			$otp = ($otp * 10) + $this->calcChecksum($otp, $this->codeDigitsNr);
		}
		
		$this->generatedCode = str_pad($otp, $digits, "0", STR_PAD_LEFT);;
		return $this->generatedCode;
	}
	
	/**
	 * Generate a new code
	 *
	 * @return string
	 */
	public function generateCode () {
		return $this->calcOTP($this->time/$this->expiration);
	}
	
	/**
	 * Validate code
	 *
	 * @param string $code
	 * @return bool
	 */
	public function validateCode ($code) {
		
		if ($code == $this->calcOTP($this->time/$this->expiration)) {
			return true;
		} else {
			$movingFactor = ($this->time-floor($this->expiration/(2)))/$this->expiration;
			return ($code == $this->calcOTP($movingFactor));
		}
	}

}
