<?php 
	namespace RRI\Rest\API\Service\Core\Components;
	
	class Configuration{
		
		private $config_data;
		
		public function __construct($config){
			$this->config_data=$config;
		}
		
		public function __get($property){
			if(array_key_exists($property,$this->config_data)){
				return $this->config_data[$property];
			}
			return null;
		}
	}