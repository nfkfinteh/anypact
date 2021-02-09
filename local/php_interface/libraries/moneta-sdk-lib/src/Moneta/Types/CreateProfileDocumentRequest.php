<?php

// Warning! This code was generated by WSDL2PHP tool. 
// author: Filippov Andrey <afi.work@gmail.com> 
// see https://solo-framework-lib.googlecode.com 

namespace Moneta\Types;

/**
 * Запрос на создание документа пользователя.
	 * Request for creating a document.
	 * 
 */
class CreateProfileDocumentRequest extends Document
{
	
	/**
	 * Пользователь, которому будет принадлежать данный документ.
	 * Если это поле не задано, то документ создается для текущего пользователя.
	 * The unique identifier of the user to whom the document belongs.
	 * If you omit this element, MONETA.RU uses the ID of the user who sends the request.
	 * 
	 *
	 * @var long
	 */
	 public $unitId = null;

	/**
	 * 
	 * 
	 *
	 * @var long
	 */
	 public $profileId = null;

}
