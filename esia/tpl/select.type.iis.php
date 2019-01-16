<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 10.04.2017
 * Time: 16:15
 */

/** @var \EsiaCore $this */

?>

<div class="clearfix"></div>

<div class="colwpl col-xs-4" id="iis_block">

    <input type="hidden" name="iis_multiply" value="N" id="iis_multiply">

    <label for="type_iis">Выберите тип рынка для ИИС <span></span></label><br>

    <select class="form-control" id="type_iis" name="type_iis">

        <?
            $TypeIISModel = [
                'IIS_STOCK' => 'Биржевой рынок',
                'IIS_OUTSTOCK' => 'Внебиржевой рынок',
            ];

            foreach ( $TypeIISModel as $TypeIISModel_Value => $TypeIISModel_Name )
            {
                ?>
                    <option value="<?= $TypeIISModel_Value ?>" <?= ( ArrayHelper::GetValuePath( $this->Detail, 'RESULT/type_iis_option' ) == $TypeIISModel_Value ) ? 'selected=""' : ''  ?>><?= $TypeIISModel_Name ?></option>
                <?
            }
        ?>




    </select>

</div>

<div class="clearfix"></div>

<script>
    document.addEventListener("DOMContentLoaded", function(event)
    {
//        var jsIisChangeHandle = $('[js-iis-change-handle]');
//
//        var market_stock = $('#market_stock');
//        var market_outstock = $('#market_outstock');
//        var iis = $('#iis');
//        var iis_block = $('#iis_block');
//
//        var iis_multiply = $('#iis_multiply');
//
//        function UpdateIIS()
//        {
//            var IM = (
//                ( market_stock.is(':checked') && market_outstock.is(':checked') && iis.is(':checked') )
//                || ( !market_stock.is(':checked') && !market_outstock.is(':checked') && iis.is(':checked') )
//            );
//
//            iis_block.toggle( IM );
//
//            iis_multiply.val( ( IM ) ? 'Y' : 'N' );
//        }
//
//        function ReSetEvents()
//        {
//            jsIisChangeHandle.on('change', function(e)
//            {
//                console.warn('jsIisChangeHandle');
//
//                var $this = $(this);
//
//                ReSetEvents();
//
//                UpdateIIS();
//            });
//
//            iis.on('change', function(e)
//            {
//                console.warn('iis ~ change');
//
//                var $this = $(this);
//
//                UpdateIIS();
//            });
//        }
//
//        ReSetEvents();

    });
</script>
