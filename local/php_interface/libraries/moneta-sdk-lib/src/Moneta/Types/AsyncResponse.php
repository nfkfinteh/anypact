<?php

// Warning! This code was generated by WSDL2PHP tool. 
// author: Filippov Andrey <afi.work@gmail.com> 
// see https://solo-framework-lib.googlecode.com 

namespace Moneta\Types;

/**
 * Ответ на асинхронный запрос.
	 * Response to an asynchronous request.
	 * 
 */
class AsyncResponse
{
	
	/**
	 * Ид запроса
	 * Id request
	 * 
	 *
	 * @var float
	 */
	public $asyncId = null;

    /**
	 * Статус запроса
	 * The status of the request
	 * 
	 *
	 * @var AsyncStatus
	 */
	public $asyncStatus = null;


    /**
	 * Срок действия
	 * Expiration date
	 * 
	 *
	 * @var date
	 */
	public $expirationDate = null;


}
