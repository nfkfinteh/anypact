<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("NFKSBER_PROFILE_NAME"),
	"DESCRIPTION" => GetMessage("NFKSBER_PROFILE_DESCRIPTION"),
	"ICON" => "",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "nfksber",
		"CHILD" => array(
			"ID" => "posts",
			"NAME" => GetMessage("NFKSBER_PROFILE"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "messenger_hl",
			),
		),
	),
);

?>