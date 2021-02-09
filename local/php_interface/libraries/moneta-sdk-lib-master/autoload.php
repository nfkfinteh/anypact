<?php

function sdk_autoloader() {

    $file_map = array('MonetaSdkStorage.php', 'MonetaSdkUtils.php', 'MonetaSdkException.php', 'MonetaSdkError.php',
                'MonetaSdkResult.php', 'MonetaSdkRender.php', 'MonetaSdkEmptyStorage.php', 'MonetaSdkFilesStorage.php',
                'MonetaSdkMysqlStorage.php', 'MonetaWebServiceConnector.php', 'MonetaWebService.php', 'MonetaSdkJsonConnector.php',
                'MonetaSdkSoapConnector.php', 'MonetaSdkMethods.php', 'MonetaSdk.php', 'MonetaSdkKassa.php',
                'MonetaSdkEmptyKassa.php', 'MonetaSdkModuleKassa.php', 'MonetaSdkAtolonlineKassa.php',
                'MonetaSdkPayanywayKassa.php', 'MonetaSdkStarrysKassa.php', 'MonetaSdkBuhsoftKassa.php',
                'MonetaSdkKomtetKassa.php', 'MonetaSdkIretailKassa.php', 'MonetaSdkOrangedataKassa.php',
		'MonetaSdkDreamkassKassa.php');

    $vendorDir = dirname(__DIR__) . "/moneta-sdk-lib/src/Moneta/";
    
    foreach ($file_map AS $one_file) {
        $filepath = $vendorDir . $one_file;
        require_once($filepath);
    }

}

function moneta_base_types_autoloader() {
    $file_map = array('Entity.php', 'EntityBatchRequestType.php', 'TransactionRequestType.php', 'VerifyTransferResponseType.php',
                      'OperationInfo.php', 'OperationInfoList.php', 'BankAccount.php', 'Document.php');

    $vendorDir = dirname(__DIR__) . "/moneta-sdk-lib/src/Moneta/Types/";
    
    foreach ($file_map AS $one_file) {
        $filepath = $vendorDir . $one_file;
        require_once($filepath);
    }

    if ($handle = opendir($vendorDir)) {
        while (false !== ($entry = readdir($handle))) {
            $filepath = $vendorDir . $entry;
            if (file_exists($filepath) && !is_dir($filepath) && strpos($entry, 'Type.php')) {
                require_once($filepath);
            }
        }
    }

    if ($handle = opendir($vendorDir)) {
        while (false !== ($entry = readdir($handle))) {
            $filepath = $vendorDir . $entry;
            if (file_exists($filepath) && !is_dir($filepath) && !strpos($entry, 'Type.php')) {
                require_once($filepath);
            }
        }
    }

}


function show_directory() {
    $vendorDir = dirname(__DIR__) . "/moneta-sdk-lib/src/Moneta/Types/";

    if ($handle = opendir($vendorDir)) {
        while (false !== ($entry = readdir($handle))) {
            $filepath = $vendorDir . $entry;
            if (file_exists($filepath) && !is_dir($filepath) && strpos($entry, 'Type.php')) {
                echo "{$filepath}<br/>";
                require_once($filepath);
            }
        }
    }
}

spl_autoload_register('moneta_base_types_autoloader');
spl_autoload_register('sdk_autoloader');
