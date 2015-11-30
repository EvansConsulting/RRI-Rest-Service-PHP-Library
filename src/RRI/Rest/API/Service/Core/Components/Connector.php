<?php
	namespace RRI\Rest\API\Service\Core\Components;

	class Connector{

		private $endpoint;
		private $version;
		private $origin;
		private $token;

		//Curl Variables
		private $ch;
		private $call_info;
		private $call_result;

		//Encryption Variables
		private $encrypt_method;
		private $encrypt_secret;
		/**
		 * @method __construct
		 * @example new Connector($config);
		 * @param Config $config
		 */

		public function __construct(Configuration $config){
			$this->endpoint=$config->endpoint;
			$this->version=$config->version;
			$this->origin=$config->origin;
			$this->encrypt_method='AES-256-CBC';
			$this->token='';
			if(array_key_exists('tr_rest_token',$_COOKIE)){
				$this->token=@openssl_decrypt($_COOKIE['tr_rest_token'], $this->encrypt_method, $this->get_secret_key());
			}
		}

		private function get_secret_key(){
			return hash('sha512',hash('sha512',hash('sha512',$this->encrypt_secret)));
		}

		private function initialize(){
			$this->ch = curl_init();
		}
		public function get_token(){
			return @openssl_encrypt($this->token,$this->encrypt_method,$this->get_secret_key());
		}
		public function save_token($token){
			$this->token=$token;
			$crypt=@openssl_encrypt($token,$this->encrypt_method,$this->get_secret_key());
			setcookie('tr_rest_token',$crypt,0,'/');
		}

		public function call_resource($resource,$method='GET',$data=array()){
			$this->initialize();
			//Build the URL
			$url = $this->endpoint.$this->version.$resource;
			curl_setopt($this->ch,CURLOPT_URL,$url);
			switch(strtoupper($method)){
				case 'POST':
					$data=http_build_query(array('request'=>base64_encode(json_encode($data))));
					curl_setopt($this->ch, CURLOPT_POST, 1);
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
				break;
				case 'PUT':
					$data=http_build_query(array('request'=>base64_encode(json_encode($data))));
					curl_setopt($this->ch, CURLOPT_POST, 1);
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
					$header=array();
					$header[]='X_HTTP_METHOD: PUT';
					curl_setopt($this->ch,CURLOPT_HTTPHEADER,$header);
				break;
				case 'DELETE':
					$data=http_build_query(array('request'=>base64_encode(json_encode($data))));
					curl_setopt($this->ch, CURLOPT_POST, 1);
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
					$header=array();
					$header[]='X_HTTP_METHOD: DELETE';
					curl_setopt($this->ch,CURLOPT_HTTPHEADER,$header);
				break;
			}
			curl_setopt($this->ch, CURLOPT_HEADER, FALSE);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($this->ch, CURLOPT_USERPWD, "{$this->origin}:{$this->token}");
			curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

			$this->call_result = curl_exec($this->ch);
			$this->call_info= curl_getinfo($this->ch);

			return $this->fetch_result();
		}

		public function fetch_result($format=TRUE)
		{
			return ($format===TRUE)?json_decode($this->call_result):$this->call_result;
		}
		/**
		 * @abstract this method will search to find the value that matches the key, if it cannot it returns the entire call information array
		 * @param string $key
		 */
		public function fetch_info($key){
			if(is_array($this->call_info) && array_key_exists($key,$this->call_info))
			{
				return $this->call_info[$key];
			}
			return $this->call_info;
		}
	}