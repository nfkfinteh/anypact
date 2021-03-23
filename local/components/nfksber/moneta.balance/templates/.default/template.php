<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

?>
<?if($arResult["CURRENT_USER"]['UF_MONETA_UNIT_ID'] > 0 && $arResult["CURRENT_USER"]['UF_MONETA_ACCOUNT_ID'] > 0 && $arResult["CURRENT_USER"]['UF_MONETA_DOC_ID'] > 0){?>
    <div class="wallet-header">
        <a href="/profile/wallet/">
            <div class="wallet-img">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0)">
                    <path d="M30.545 16.1342V11.6367C30.545 10.1116 29.3621 8.87133 27.8675 8.75106L23.6907 1.45572C23.3037 0.781005 22.6787 0.298763 21.9308 0.0985167C21.1865 -0.100366 20.4081 0.00408642 19.7419 0.39183L5.42378 8.7276H2.90907C1.30471 8.7276 0 10.0322 0 11.6367V29.0909C0 30.6953 1.30464 32 2.90907 32H27.636C29.2403 32 30.545 30.6954 30.545 29.0909V24.5934C31.3897 24.2922 31.9995 23.4925 31.9995 22.5456V18.182C31.9995 17.2351 31.3897 16.4354 30.545 16.1342ZM20.4741 1.64888C20.8036 1.4564 21.1879 1.40527 21.5544 1.50331C21.9258 1.60272 22.2355 1.84278 22.428 2.17871L23.8451 4.65401C23.1988 4.94132 22.5089 5.09132 21.8179 5.09132C20.2559 5.09132 18.8247 4.38245 17.864 3.16842L20.4741 1.64888ZM16.6006 3.90395C17.8342 5.56709 19.7334 6.54589 21.8179 6.54589C22.7615 6.54589 23.697 6.32341 24.5663 5.91372L26.1774 8.72767H8.31464L16.6006 3.90395ZM29.0905 29.0909C29.0905 29.8927 28.4378 30.5454 27.636 30.5454H2.90907C2.10726 30.5454 1.45457 29.8927 1.45457 29.0909V11.6367C1.45457 10.8349 2.10726 10.1822 2.90907 10.1822H27.636C28.4378 10.1822 29.0905 10.8349 29.0905 11.6367V16.0002H24.7269C22.3207 16.0002 20.3633 17.9576 20.3633 20.3638C20.3633 22.77 22.3207 24.7274 24.7269 24.7274H29.0905V29.0909ZM30.545 22.5456C30.545 22.9468 30.2191 23.2729 29.8177 23.2729H24.7269C23.1225 23.2729 21.8178 21.9682 21.8178 20.3638C21.8178 18.7594 23.1225 17.4547 24.7269 17.4547H29.8177C30.219 17.4547 30.545 17.7807 30.545 18.182V22.5456Z" fill="#999999"/>
                    <path d="M24.726 18.9093C23.9242 18.9093 23.2715 19.562 23.2715 20.3638C23.2715 21.1656 23.9242 21.8183 24.726 21.8183C25.5278 21.8183 26.1805 21.1656 26.1805 20.3638C26.1805 19.562 25.5279 18.9093 24.726 18.9093Z" fill="#999999"/>
                    </g>
                    <defs>
                    <clipPath id="clip0">
                    <rect width="32" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
            </div>
            <p><?=number_format($arResult["CURRENT_USER"]['UF_MONETA_BALANCE'], 2, ',', ' ');?> ₽</p>
        </a>
    </div>
<?}else{?>
    <div class="wallet-header no-reg-wallet-js">
        <a href="#">
            <div class="wallet-img">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0)">
                    <path d="M30.545 16.1342V11.6367C30.545 10.1116 29.3621 8.87133 27.8675 8.75106L23.6907 1.45572C23.3037 0.781005 22.6787 0.298763 21.9308 0.0985167C21.1865 -0.100366 20.4081 0.00408642 19.7419 0.39183L5.42378 8.7276H2.90907C1.30471 8.7276 0 10.0322 0 11.6367V29.0909C0 30.6953 1.30464 32 2.90907 32H27.636C29.2403 32 30.545 30.6954 30.545 29.0909V24.5934C31.3897 24.2922 31.9995 23.4925 31.9995 22.5456V18.182C31.9995 17.2351 31.3897 16.4354 30.545 16.1342ZM20.4741 1.64888C20.8036 1.4564 21.1879 1.40527 21.5544 1.50331C21.9258 1.60272 22.2355 1.84278 22.428 2.17871L23.8451 4.65401C23.1988 4.94132 22.5089 5.09132 21.8179 5.09132C20.2559 5.09132 18.8247 4.38245 17.864 3.16842L20.4741 1.64888ZM16.6006 3.90395C17.8342 5.56709 19.7334 6.54589 21.8179 6.54589C22.7615 6.54589 23.697 6.32341 24.5663 5.91372L26.1774 8.72767H8.31464L16.6006 3.90395ZM29.0905 29.0909C29.0905 29.8927 28.4378 30.5454 27.636 30.5454H2.90907C2.10726 30.5454 1.45457 29.8927 1.45457 29.0909V11.6367C1.45457 10.8349 2.10726 10.1822 2.90907 10.1822H27.636C28.4378 10.1822 29.0905 10.8349 29.0905 11.6367V16.0002H24.7269C22.3207 16.0002 20.3633 17.9576 20.3633 20.3638C20.3633 22.77 22.3207 24.7274 24.7269 24.7274H29.0905V29.0909ZM30.545 22.5456C30.545 22.9468 30.2191 23.2729 29.8177 23.2729H24.7269C23.1225 23.2729 21.8178 21.9682 21.8178 20.3638C21.8178 18.7594 23.1225 17.4547 24.7269 17.4547H29.8177C30.219 17.4547 30.545 17.7807 30.545 18.182V22.5456Z" fill="#999999"/>
                    <path d="M24.726 18.9093C23.9242 18.9093 23.2715 19.562 23.2715 20.3638C23.2715 21.1656 23.9242 21.8183 24.726 21.8183C25.5278 21.8183 26.1805 21.1656 26.1805 20.3638C26.1805 19.562 25.5279 18.9093 24.726 18.9093Z" fill="#999999"/>
                    </g>
                    <defs>
                    <clipPath id="clip0">
                    <rect width="32" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
            </div>
            <p>Открыть счет</p>
        </a>
    </div>
<?}?>