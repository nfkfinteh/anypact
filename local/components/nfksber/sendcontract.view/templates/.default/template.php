<? // print_r($arResult["PROPERTY"]) ;?>
<? //print_r($arResult) ;?>
<h4>Просмотр договора</h4>
<style>
    .view-pdf{
        width:100%;
    }
</style>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="cardDogovor-boxTool">
                <a href="/my_pacts/"> << Назад</a>
                <style>
                    .tools_view_contract{
                        width: 100%;
                        height: 50px;
                        border: 1px solid #607D8B;
                    }

                    .button_tool{
                        float: left;                        
                        margin: 5px;
                        cursor: pointer;
                    }
                    .button_tool img{
                        width: 38px;
                    }
                </style>
                <div class="tools_view_contract">
                    <div class="button_tool">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/downloading-file.svg" alt="">
                    </div>
                    <div class="button_tool">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/printer.svg" alt="">
                    </div>
                </div>
                <div class="view-pdf" style="border: #9E9E9E 13px solid; padding: 20px;">
                    <div style="wight:100%"><?=$arResult['CONTRACT_TEXT']?></div>
                </div>
            </div>
        </div>        
    </div>
</div>