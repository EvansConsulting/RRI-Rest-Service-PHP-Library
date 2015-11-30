<?php
	namespace RRI\Rest\API\Service\Core\Components;
	
	class Base
	{
		protected $config;
		protected $connector;
		
		const METHOD_GET	= 'GET';
		const METHOD_POST	= 'POST';
		const METHOD_PUT 	= 'PUT';
		const METHOD_DELETE = 'DELETE';
		
		public function __construct($config){
			$this->config= new Configuration($config);
			$this->connector = new Connector($this->config);
		}
	}
	