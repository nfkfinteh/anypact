<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div>
</div>
<div class="container wallet-container">
    <div class="wallet-container_col">
        <?$APPLICATION->IncludeComponent("nfksber:moneta.wallet.info","",Array('ACTION_VARIABLE' => 'action'));?>
        <?$APPLICATION->IncludeComponent("nfksber:moneta.wallet.payments","",Array('ACTION_VARIABLE' => 'action'));?>
    </div>
    <?$APPLICATION->IncludeComponent("nfksber:moneta.wallet.history","",Array('ACTION_VARIABLE' => 'action'));?>
</div>