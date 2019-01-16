<?
$ClassificatorsAvaillabled = [];
$ClassificatorsAvaillabled[] = [
	"IS_OUTER_STOCK" => "Внебиржевой рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_STOCK" => "Биржевой рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_CURRENCY" => "Валютный рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_OTHER" => "Иной рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_OTHER_OWNER_TRADE" => "Иной рынок > Владелец ( Торговый )",
];
$ClassificatorsAvaillabled[] = [
	"IS_OTHER_OWNER" => "Иной рынок > Владелец",
];
$ClassificatorsAvaillabled[] = [
	"IS_OTHER_BROKER" => "Иной рынок > Брокер",
];
$ClassificatorsAvaillabled[] = [
	"IS_OTHER_REQUISITES" => "Иной рынок > Реквизиты",
];
$ClassificatorsAvaillabled[] = [
	"IS_IIS" => "ИИС",
];
$ClassificatorsAvaillabled[] = [
	"IS_IIS_CONTRACT_LESS" => "ИИС > Контракт отсутствует",
];
$ClassificatorsAvaillabled[] = [
	"IS_IIS_CONTRACT_HAVE" => "ИИС > Контракт имеется",
];
$ClassificatorsAvaillabled[] = [
	"IS_IIS_TYPE_STORE" => "ИИС >  Биржевой рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_IIS_TYPE_OUTSTORE" => "ИИС > Внебиржевой рынок",
];
$ClassificatorsAvaillabled[] = [
	"IS_USER_RESIDENCE" => "Вида на жительство другого нет",
];
$ClassificatorsAvaillabled[] = [
	"IS_AGREE_DOCUMENTS_TAX" => "Согласие на передачу данных инстранного",
];
$ClassificatorsAvaillabled[] = [
	"IS_USER_CITIZENSHIP" => "Гражданства другого нет",
];
$ClassificatorsAvaillabled[] = [
	"NOT_USER_RESIDENCE" => "Есть другой вид на жительство",
];
$ClassificatorsAvaillabled[] = [
	"NOT_AGREE_DOCUMENTS_TAX" => "Не согласен на передачу информации",
];
$ClassificatorsAvaillabled[] = [
	"NOT_USER_CITIZENSHIP" => "Есть другое гражданство",
];
$GroupsAvaillabled = [];
$GroupsAvaillabled[ BIRJ_KOMPL ] = "Брокерский комплект";
$GroupsAvaillabled[ VNEBIRJ_KOMPL ] = "Внебиржевой комплект";
$GroupsAvaillabled[ VALUTA_KOMPL ] = "Валютный комплект";
$GroupsAvaillabled[ DEPO_KOMPL ] = "Депозитарный комплект";
$GroupsAvaillabled[ DEPO_DOPOLN_KOMPL ] = "Депозитарный комплект дополнительный";
$GroupsAvaillabled[ IIS_VNEBIRJ_KOMPL ] = "ИИС внебиржевой комплект";
$GroupsAvaillabled[ IIS_BIRJ_KOMPL ] = "ИИС брокерский комплект";
$GroupsAvaillabled[ BIRJ_KOMPL ] = "Брокерский комплект";
$GroupsAvaillabled[ VNEBIRJ_KOMPL ] = "Внебиржевой комплект";
$GroupsAvaillabled[ VALUTA_KOMPL ] = "Валютный комплект";
$GroupsAvaillabled[ DEPO_KOMPL ] = "Депозитарный комплект";
$GroupsAvaillabled[ DEPO_DOPOLN_KOMPL ] = "Депозитарный комплект дополнительный";
$GroupsAvaillabled[ IIS_VNEBIRJ_KOMPL ] = "ИИС внебиржевой комплект";
$GroupsAvaillabled[ IIS_BIRJ_KOMPL ] = "ИИС брокерский комплект";
$DocumentsModel = [];
$DocumentsModel[] = [
	"CODE" => "ANKETA_UPROSHEN",
	"FILE" => "#DOCUMENT_DIR#anketa_uproshen.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (упрощенная)",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
					"IS_IIS_TYPE_OUTSTORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
					"IS_IIS_TYPE_OUTSTORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_CURRENCY",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_USER_RESIDENCE",
					"IS_USER_CITIZENSHIP",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "BROKER_CONRTACT",
	"FILE" => "#DOCUMENT_DIR#1_broker_komplekt.pdf?id=#ID_PERSON#",
	"NAME" => "Договор на брокерское обслуживание",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "OR",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "2_DEPO_DOG_F",
	"FILE" => "#DOCUMENT_DIR#2_depo_dog_f.pdf?id=#ID_PERSON#",
	"NAME" => "Депозитарный договор счета депо",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_DOGOVOR_UIS_ST",
	"FILE" => "#DOCUMENT_DIR#1_dogovor_uis_st.pdf?id=#ID_PERSON#",
	"NAME" => "Договор универсального инвестиционного счета стандарт",
	"GROUP" => "VNEBIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_BROKER_KOMPLEKT_IIS",
	"FILE" => "#DOCUMENT_DIR#1_broker_komplekt_iis.pdf?id=#ID_PERSON#",
	"NAME" => "Договор на брокерское обслуживание ИИС",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
					"IS_OTHER",
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_DOGOVOR_UIS_IIS_BEZ_PEREVODA",
	"FILE" => "#DOCUMENT_DIR#1_dogovor_uis_iis_bez_perevoda.pdf?id=#ID_PERSON#",
	"NAME" => "Договор универсального инвестиционного счета без перевода",
	"GROUP" => "IIS_VNEBIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS_TYPE_STORE",
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
					"IS_IIS_TYPE_OUTSTORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_BROKER_KOMPLEKT_IIS_PRED",
	"FILE" => "#DOCUMENT_DIR#1_broker_komplekt_iis_pred.pdf?id=#ID_PERSON#",
	"NAME" => "Договор на брокерское обслуживание ИИС (ИИС имеется)",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
					"IS_OTHER",
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_DOGOVOR_UIS_IIS_S_PEREVODOM",
	"FILE" => "#DOCUMENT_DIR#1_dogovor_uis_iis_s_perevodom.pdf?id=#ID_PERSON#",
	"NAME" => "Договор универсального инвестиционного счета с переводом",
	"GROUP" => "IIS_VNEBIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
					"IS_IIS_TYPE_OUTSTORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "1_VALUTA_DOGOVOR",
	"FILE" => "#DOCUMENT_DIR#1_valuta_dogovor.pdf?id=#ID_PERSON#",
	"NAME" => "Договор оказания услуг на валютном рынке и рынке драгоценных металлов",
	"GROUP" => "VALUTA_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_CURRENCY",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "2_DEPO_DOG_F_DOPOLN",
	"FILE" => "#DOCUMENT_DIR#2_depo_dog_f_dopoln.pdf?id=#ID_PERSON#",
	"NAME" => "Депозитарный договор дополнительный",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "2_DEPO_DOG_F_DOPOLN_IIS",
	"FILE" => "#DOCUMENT_DIR#2_depo_dog_f_dopoln_iis.pdf?id=#ID_PERSON#",
	"NAME" => "Депозитарный договор дополнительный ИИС",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_DOPOLN",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_dopoln.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица на депозитарный счет дополнительный",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_REKVIZITY",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_rekvizity.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица по реквизитам",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_DOPOLN_VNEBIRJ",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_dopoln_vnebirj.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица на депозитарный счет дополнительный для внебиржего рынка",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_DOPOLN_VLADELEC",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_dopoln_vladelec.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица на депозитарный счет дополнительный владельца",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_REKVIZITY_VLADELCA",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_rekvizity_vladelca.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица по реквизитам владельца",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "4_ANKETA_CLIENT_F_DOPOLN_IIS",
	"FILE" => "#DOCUMENT_DIR#4_anketa_client_f_dopoln_iis.pdf?id=#ID_PERSON#",
	"NAME" => "Анкета клиента (Депонента) физического лица на депозитарный счет дополнительный ИИС",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "5_PORUCHENIE_OPEN_CHET_DEPO_F_TORG",
	"FILE" => "#DOCUMENT_DIR#5_poruchenie_open_chet_depo_f_torg.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие счета депо (для физических лиц)",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "6_PORUCHENIE_OPEN_RAZDEL_CHET_DEPO",
	"FILE" => "#DOCUMENT_DIR#6_poruchenie_open_razdel_chet_depo.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие раздела счета депо",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
	],
];
$DocumentsModel[] = [
	"CODE" => "6_PORUCHENIE_OPEN_RAZDEL_CHET_DEPO_TORG",
	"FILE" => "#DOCUMENT_DIR#6_poruchenie_open_razdel_chet_depo_torg.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие раздела счета депо (для физических лиц)",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER_TRADE",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "5_PORUCHENIE_OPEN_CHET_F_VLADELCA",
	"FILE" => "#DOCUMENT_DIR#5_poruchenie_open_chet_depo_f_vladelca.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие счета депо (для физических лиц) владельца",
	"GROUP" => "DEPO_DOPOLN_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_BROKER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_OUTER_STOCK",
					"IS_OTHER",
					"IS_OTHER_OWNER",
					"IS_OTHER_REQUISITES",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "5_PORUCHENIE_OPEN_CHET_DEPO_F_TORG_IIS",
	"FILE" => "#DOCUMENT_DIR#5_poruchenie_open_chet_depo_f_torg_iis.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие счет депо (для физических лиц) ИИС",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "6_PORUCHENIE_OPEN_RAZDEL_CHET_DEPO_TORG_IIS",
	"FILE" => "#DOCUMENT_DIR#6_poruchenie_open_razdel_chet_depo_torg_iis.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие раздела счета депо (для физических лиц) ИИС",
	"GROUP" => "IIS_BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_TYPE_STORE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_LESS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_IIS",
					"IS_IIS_CONTRACT_HAVE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_GR_SOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_gr_sogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_USER_RESIDENCE",
					"IS_AGREE_DOCUMENTS_TAX",
					"NOT_USER_CITIZENSHIP",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_GR_NESOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_gr_nesogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_USER_RESIDENCE",
					"NOT_AGREE_DOCUMENTS_TAX",
					"NOT_USER_CITIZENSHIP",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_VIDNAJ_SOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_vidnaj_sogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_AGREE_DOCUMENTS_TAX",
					"IS_USER_CITIZENSHIP",
					"NOT_USER_RESIDENCE",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_VIDNAJ_NESOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_vidnaj_nesogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_USER_CITIZENSHIP",
					"NOT_USER_RESIDENCE",
					"NOT_AGREE_DOCUMENTS_TAX",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_GR_VIDNAJ_SOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_gr_vidnaj_sogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_AGREE_DOCUMENTS_TAX",
					"NOT_USER_RESIDENCE",
					"NOT_USER_CITIZENSHIP",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "OPROSNIK_INOSTR_GR_VIDNAJ_NESOGL",
	"FILE" => "#DOCUMENT_DIR#oprosnik_inostr_gr_vidnaj_nesogl.pdf?id=#ID_PERSON#",
	"NAME" => "Опросник иностранные налогоплательщики",
	"GROUP" => "",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"NOT_USER_RESIDENCE",
					"NOT_AGREE_DOCUMENTS_TAX",
					"NOT_USER_CITIZENSHIP",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "5_poruchenie_open_chet_depo_f_birj",
	"FILE" => "#DOCUMENT_DIR#5_poruchenie_open_chet_depo_f_birj.pdf?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие счета депо",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_OTHER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
					"IS_OTHER",
					"IS_IIS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
$DocumentsModel[] = [
	"CODE" => "6_poruchenie_open_razdel_chet_depo_birj",
	"FILE" => "#DOCUMENT_DIR#6_poruchenie_open_razdel_chet_depo_birj?id=#ID_PERSON#",
	"NAME" => "Поручение на открытие раздела счета депо ",
	"GROUP" => "BIRJ_KOMPL",
	"VARIANTS" => [
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_OTHER",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_IIS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
		[
			"NAME" => "",
			"INCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
					"IS_STOCK",
					"IS_CURRENCY",
					"IS_OTHER",
					"IS_IIS",
				],
			],
			"EXCLUDE" => [
				"LOGIC" => "AND",
				"CLASSIFIERS" => [
				],
			],
		],
	],
];
