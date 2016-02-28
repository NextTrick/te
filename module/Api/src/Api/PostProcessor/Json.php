<?php

namespace Api\PostProcessor;

use Zend\Json\Encoder;

class Json extends AbstractPostProcessor
{
	public function process()
	{
		$result = Encoder::encode($this->_vars);

		$this->_response->setContent($result);

		$headers = $this->_response->getHeaders();
		$headers->addHeaderLine('Content-Type', 'application/json');
		$this->_response->setHeaders($headers);
	}
}
