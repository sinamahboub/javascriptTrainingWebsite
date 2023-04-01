<?php
class Encryption{
	
    protected $method = 'AES-256-CBC';//AES-256-ECB
    protected $key = "df_66Aq_E#$!*&][O"; // you can change it
	
	public function setKey($key){
		$strLen=strlen($key);
		if($strLen=="16" ||  $strLen=="24" || $strLen=="32" )
			$this->key=$key;
		else
			echo Msg::msgError("اندازه کلید باید 16،24 و یا 32 باشد");
	}
	
    public  function safe_b64encode($string) {
	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    /**
     * Encrypts the data
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to FALSE to prevent base64-encoded 
     * @return string (raw binary)
     */
    public  function encode($message, $key="", $encode = true)
    {
        if($key=="")
        {
            $key=$this->key;
        }
        
        $nonceSize = openssl_cipher_iv_length($this->method);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        try{
			$ciphertext = @openssl_encrypt($message,$this->method,$key,OPENSSL_RAW_DATA,$nonce);
			$sendEncrypt=$nonce.$ciphertext;
			if(!$ciphertext)
			   exit('Unable To Encrypt Data, change method in Encryption');
        }catch(Exception $e){
            $encode=false;
            echo $sendEncrypt=$e->getMessage();
        }

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            $sendEncrypt=$this->safe_b64encode($sendEncrypt);
        }
        return $sendEncrypt;
    }

    /**
     * Decrypts the data
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - set to FALSE to prevent base64-decode
     * @return string
     */
    public  function decode($message, $key="", $encoded = true)
    {
        if($key=="")
        {
            $key=$this->key;
        }
        
        if ($encoded) {
         try{
            $message = $this->safe_b64decode($message, true);
            if ($message === false) {
                exit('Unable To Decrypt Data, change method in Encryption');
            }else{
                $nonceSize = openssl_cipher_iv_length($this->method);
                $nonce = mb_substr($message, 0, $nonceSize, '8bit');
                $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

                $plaintext = @openssl_decrypt($ciphertext,$this->method,$key,OPENSSL_RAW_DATA,$nonce);
            }
         }catch(Exception $e){
            $plaintext=$e->getMessage();
         }
        }

        return $plaintext;
    }
	
	public function makeArray($reciveString){
        $var=$this->decode($reciveString);
        $var=explode(",",$var);
        foreach($var as $value){
            $key=strstr($value,"=",true);
            $val=ltrim(strstr($value,"="),"=");
            $data[$key]=$val;
        }
        return $data;
    }
}

