<?php 
	namespace RRI\Rest\API\Service;
	
	class Client extends Core\Components\Base{
		
		
		public function __construct($origin,$version,$key,$endpoint)
		{
			$config=array(
				'origin'=>$origin,
				'version'=>$version,
				'secret_key'=>$key,
				'endpoint'=>$endpoint
			);
			
			parent::__construct($config);
		}
		
		/**
		 * @abstract This method is used to login to the service and use the connector class to save the token with no additional work on the implementor.  The User Information is returned to be used however the client needs
		 * @param string $username
		 * @param string $password
		 * @return NULL
		 * @return \stdClass
		 */
		public function login($username,$password){
			$result=$this->call(FALSE,'/login',self::METHOD_POST,array('un'=>$username,'pw'=>$password));
			if($result->status==0){
				return NULL;
			}
			//Save the token
			$this->connector->save_token($result->token);
			return $result->user;
		}
		
		public function call($assoc,$resource,$method=self::METHOD_GET,$data=array()){
			if($assoc!==TRUE)
			{
				$assoc=FALSE;
			}
			$result=$this->connector->call_resource($resource,$method,$data);
			return ($assoc==TRUE)?json_decode(json_encode($result),TRUE):$result;
		}
	}
