<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.10.2017
 * Time: 17:21
 */

?>

<div id="ar_res_hidden" style="display: none;">
    <pre>
        <?= json_encode( $arResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) ?>
    </pre>
</div>
