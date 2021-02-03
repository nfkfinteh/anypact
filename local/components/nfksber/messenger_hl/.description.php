<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("NFKSBER_HL_MESSENGER_NAME"),
	"DESCRIPTION" => GetMessage("NFKSBER_HL_MESSENGER_DESCRIPTION"),
	"ICON" => "",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "nfksber",
		"CHILD" => array(
			"ID" => "messenger",
			"NAME" => GetMessage("NFKSBER_MESSENGER"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "messenger_hl",
			),
		),
	),
);

?>