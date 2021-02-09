<?php

// Warning! This code was generated by WSDL2PHP tool. 
// author: Filippov Andrey <afi.work@gmail.com> 
// see https://solo-framework-lib.googlecode.com 

namespace Moneta\Types;

/**
 * Запрос на удаление делегированного доступа к счету.
	 * Request for deletion of access delegation information.
	 * 
 */
class DeleteAccountRelationRequest
{
	
	/**
	 * Номер счета в системе МОНЕТА.РУ.
	 * MONETA.RU account number.
	 * 
	 *
	 * @var long
	 */
	 public $accountId = null;

	/**
	 * Email пользователя.
	 * Email address of the user whose delegated access to your MONETA.RU account you want to revoke.
	 * 
	 *
	 * @var string
	 */
	 public $principalEmail = null;

}
