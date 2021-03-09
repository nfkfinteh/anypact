<?php

// Warning! This code was generated by WSDL2PHP tool. 
// author: Filippov Andrey <afi.work@gmail.com> 
// see https://solo-framework-lib.googlecode.com 

namespace Moneta\Types;

/**
 * Тип, описывающий документ в системе МОНЕТА.РУ.
	 * Specifies information about a document in MONETA.RU.
	 * 
 */
class Document
{
	
	/**
	 * Идентификатор документа в системе МОНЕТА.РУ.
	 * Unique identifier of a document in MONETA.RU.
	 * 
	 *
	 * @var long
	 */
	 public $id = null;

	/**
	 * Тип документа.
	 * Document type.
	 * 
	 *
	 * @var string
	 */
	 public $type = null;

	/**
	 * Поля документа в системе МОНЕТА.РУ.
	 * Данные представляются в виде "ключ-значение" и признака подтвержденности.
	 * В зависимости от типа документа возвращаются следующие поля.
	 * Для документов типа PASSPORT, MILITARY_ID:
	 * SERIES. Серия документа.
	 * NUMBER. Номер документа.
	 * ISSUER. Кем выдан документ.
	 * ISSUED. Когда выдан документ.
	 * COMMENTS. Комментарии (необязательное поле).
	 * Для документов типа DRIVING_LICENCE:
	 * SERIES. Серия документа.
	 * NUMBER. Номер документа.
	 * ISSUER. Кем выдан документ.
	 * ISSUED. Когда выдан документ.
	 * EXPIRATIONDATE. Срок действия.
	 * COMMENTS. Комментарии (необязательное поле).
	 * Для OTHER:
	 * COMMENTS. Комментарии, пояснения, описание.
	 * Для всех типов документов:
	 * customfield:*. Произвольный набор значений.
	 * В документе их может быть несколько.
	 * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа).
	 * Например, "customfield:name".
	 * MODIFICATIONDATE. Последняя дата редактирования документа.
	 * Document attributes in MONETA.RU.
	 * Document information is specified in a list of key-value pairs
	 * Valid keys for the PASSPORT and MILITARY_ID document types are:
	 * SERIES. Document series.
	 * NUMBER. Document number.
	 * ISSUER. Issuing authority.
	 * ISSUED. Issue date.
	 * COMMENTS. Optional description.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * Valid keys for the DRIVING_LICENCE document type:
	 * SERIES. Document series.
	 * NUMBER. Document number.
	 * ISSUER. Issuing authority.
	 * ISSUED. Issue date.
	 * EXPIRATIONDATE. Expiration date.
	 * COMMENTS. Optional description.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * Valid keys for the OTHER document type:
	 * COMMENTS. Document name, description, comments, or explanation.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * 
	 *
	 * @var KeyValueAttribute
	 */
	 public $attribute = null;

	/**
	 * Поля документа в системе МОНЕТА.РУ.
	 * Данные представляются в виде "ключ-значение" и признака подтвержденности.
	 * В зависимости от типа документа возвращаются следующие поля.
	 * Для документов типа PASSPORT, MILITARY_ID:
	 * SERIES. Серия документа.
	 * NUMBER. Номер документа.
	 * ISSUER. Кем выдан документ.
	 * ISSUED. Когда выдан документ.
	 * COMMENTS. Комментарии (необязательное поле).
	 * Для документов типа DRIVING_LICENCE:
	 * SERIES. Серия документа.
	 * NUMBER. Номер документа.
	 * ISSUER. Кем выдан документ.
	 * ISSUED. Когда выдан документ.
	 * EXPIRATIONDATE. Срок действия.
	 * COMMENTS. Комментарии (необязательное поле).
	 * Для OTHER:
	 * COMMENTS. Комментарии, пояснения, описание.
	 * Для всех типов документов:
	 * customfield:*. Произвольный набор значений.
	 * В документе их может быть несколько.
	 * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа).
	 * Например, "customfield:name".
	 * MODIFICATIONDATE. Последняя дата редактирования документа.
	 * Document attributes in MONETA.RU.
	 * Document information is specified in a list of key-value pairs
	 * Valid keys for the PASSPORT and MILITARY_ID document types are:
	 * SERIES. Document series.
	 * NUMBER. Document number.
	 * ISSUER. Issuing authority.
	 * ISSUED. Issue date.
	 * COMMENTS. Optional description.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * Valid keys for the DRIVING_LICENCE document type:
	 * SERIES. Document series.
	 * NUMBER. Document number.
	 * ISSUER. Issuing authority.
	 * ISSUED. Issue date.
	 * EXPIRATIONDATE. Expiration date.
	 * COMMENTS. Optional description.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * Valid keys for the OTHER document type:
	 * COMMENTS. Document name, description, comments, or explanation.
	 * MODIFICATIONDATE. The date of the last change in the document.
	 * customfield:custom_attribute_name
	 * . Custom attribute.
	 * A document might include multiple custom attributes.
	 * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
	 * 
	 *
	 * @param KeyValueAttribute
	 *
	 * @return void
	 */
	public function addAttribute(KeyValueAttribute $item)
	{
		$this->attribute[] = $item;
	}

	/**
	 * Имеет ли документ прикрепленные файлы.
	 * Для получения прикрепленных файлов используйте вызов FindProfileDocumentFilesRequest.
	 * Indicates whether the document has attachments.
	 * To get an attachment, use FindProfileDocumentFilesRequest.
	 * 
	 *
	 * @var boolean
	 */
	 public $hasAttachedFiles = null;

}