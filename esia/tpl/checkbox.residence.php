<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 10.04.2017
 * Time: 12:55
 */

/** @var \EsiaCore $this */
?>

<div class="colwpp col-xs-12 element_control">
    <input id="ch_tax" class="checkbox" type="checkbox" <?= XCheckedCurrent($this, 'is_tax', 'N', true ) ?> value="Y">
    <label for="ch_tax">Подтверждаю что не имею одновременно с гражданством
        Российской Федерации гражданство иностранного
        государства (за исключением гражданства
        государства - члена Таможенного союза)</label>
    <input type="hidden" name="is_tax" value="N" id="is_tax">
</div>

<div class="colwpl col-xs-8" <?= ( ArrayHelper::GetValuePath( $this->Detail, 'RESULT/is_tax' ) != 'N' ) ? 'style="display: none;"' : '' ?> id="tax_block" js-tax-block>
    <br>
    <label for="tax">Страна <span></span></label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="tax"
                size="61"
                name="tax"
                maxlength="80"
                placeholder="Страна"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'tax' ) ?>"
        >
    </div>
</div>

<div class="clearfix"></div>
<br />
<div class="colwpp col-xs-12 element_control">
    <input id="ch_residence" class="checkbox" type="checkbox" <?= XCheckedCurrent($this, 'is_residence', 'N', true ) ?> value="Y">
    <label for="ch_residence">Подтверждаю, что не имею вид на жительство в иностранном государстве.</label>
    <input type="hidden" name="is_residence" value="N" id="is_residence">
</div>

<div class="colwpl col-xs-8" <?= ( ArrayHelper::GetValuePath( $this->Detail, 'RESULT/is_residence' ) != 'N' ) ? 'style="display: none;"' : '' ?> id="residence_block" js-residence-block>
    <br>
    <label for="residence">Страна <span></span></label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="residence"
                size="61"
                name="residence"
                maxlength="80"
                placeholder="Страна"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'residence' ) ?>"
        >
    </div>
</div>

<div class="clearfix"></div>

<div class="colwpp col-xs-12" style="display: none" js-agree-documents-tax>
    <br>
    <input id="ch_residence_info" class="checkbox" type="checkbox" checked="checked" value="Y">
    <label for="ch_residence_info">Согласен согласие на передачу информации в иностранный налоговый орган и (или) иностранным налоговым агентам, уполномоченным иностранным налоговым органом.
        (данная информация может быть необходима при расчете и исчислении налогов, связанных с операциями на финансовом рынке)</label>
    <input type="hidden" name="is_residence_info" value="Y" id="is_residence_info">
</div>

<div class="clearfix"></div>

<br />

<div class="colwpp col-xs-12 element_control">
    <input id="ch_nalog_residence" class="checkbox" type="checkbox" <?= XCheckedCurrent($this, 'a1', 'N', true ) ?> value="Y" js-dynamic-checkbox='<?= json_encode( [ 'TARGET' => '#is_nalog_residence', 'CHECKED' => 'N', 'UNCHECKED' => 'Y', 'CONTROL' => '[js-nalog-residence-block]' ] ) ?>'>
    <label for="ch_nalog_residence">Подтверждаю, что не являюсь налоговым резидентом иностранных государств (территорий)</label>
    <input type="hidden" name="a1" value="N" id="is_nalog_residence">
</div>

<div class="clearfix"></div>

<div class="colwpl col-xs-8" <?= ( ArrayHelper::GetValuePath( $this->Detail, 'RESULT/is_nalog_residence' ) != 'N' ) ? 'style="display: none;"' : '' ?> id="is_nalog_residence_block" js-nalog-residence-block js-dynamic-checkbox-visibled-value="Y">
    <br>
    <label for="state_of_tax_residence">Государство (территория) налогового резидентства</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="state_of_tax_residence"
                size="61"
                name="a2"
                maxlength="80"
                placeholder="Государство (территория) налогового резидентства"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a2' ) ?>"
        >
    </div>

    <br>
    <label for="foreign_tax_identification_number">Иностранный идентификационный номер налогоплательщика, присвоенный иностранным государством (территорией), налоговым резидентом которой вы являетесь</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="foreign_tax_identification_number"
                size="61"
                name="a3"
                maxlength="80"
                placeholder="Иностранный идентификационный номер налогоплательщика"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a3' ) ?>"
        >
    </div>
</div>

<div class="clearfix"></div>

<br />

<div class="colwpp col-xs-12 element_control">
    <input id="ch_beneficiar_nalog_residence" class="checkbox" type="checkbox" <?= XCheckedCurrent($this, 'a4', 'N', true ) ?> value="Y" js-dynamic-checkbox='<?= json_encode( [ 'TARGET' => '#is_beneficiar_nalog_residence', 'CHECKED' => 'N', 'UNCHECKED' => 'Y', 'CONTROL' => '[js-beneficiar-nalog-residence-block]' ] ) ?>'>
    <label for="ch_beneficiar_nalog_residence">Подтверждаю, что не имею выгодоприобретателей, являющихся налоговыми резидентами иностранных государств (территорий)</label>
    <input type="hidden" name="a4" value="N" id="is_beneficiar_nalog_residence">
</div>

<div class="clearfix"></div>

<div class="colwpl col-xs-8" <?= ( ArrayHelper::GetValuePath( $this->Detail, 'RESULT/is_nalog_residence' ) != 'N' ) ? 'style="display: none;"' : '' ?> id="is_nalog_residence_block" js-beneficiar-nalog-residence-block js-dynamic-checkbox-visibled-value="Y">
    <br>
    <label for="fio_of_tax_residence_beneficiary">Фамилия, имя, отчество (при наличии) выгодоприобретателя</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="fio_of_tax_residence_beneficiary"
                size="61"
                name="a7"
                maxlength="80"
                placeholder="Фамилия, имя, отчество (при наличии) выгодоприобретателя"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a7' ) ?>"
        >
    </div>

    <br>
    <label for="bd_of_tax_residence_beneficiary">Дата и место рождения выгодоприобретателя</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="bd_of_tax_residence_beneficiary"
                size="61"
                name="a8"
                maxlength="80"
                placeholder="Дата и место рождения выгодоприобретателя"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a8' ) ?>"
        >
    </div>

    <br>
    <label for="adr_of_tax_residence_beneficiary">Адрес места жительства (регистрации) или адрес места пребывания выгодоприобретателя</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="adr_of_tax_residence_beneficiary"
                size="61"
                name="a9"
                maxlength="80"
                placeholder="Адрес места жительства (регистрации) или адрес места пребывания выгодоприобретателя"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a9' ) ?>"
        >
    </div>

    <br>
    <label for="state_of_tax_residence_beneficiary">Государство (территория) налогового резидентства выгодоприобретателя</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="state_of_tax_residence_beneficiary"
                size="61"
                name="a5"
                maxlength="80"
                placeholder="Государство (территория) налогового резидентства выгодоприобретателя"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a5' ) ?>"
        >
    </div>

    <br>
    <label for="foreign_tax_identification_number_beneficiary">Иностранный идентификационный номер налогоплательщика, присвоенный иностранным государством (территорией), налоговым резидентом которой является выгодоприобретатель</label><br>
    <div
            style="width: 678px;"
    >
        <input
                type="text"
                class="form-control"
                id="foreign_tax_identification_number_beneficiary"
                size="61"
                name="a6"
                maxlength="80"
                placeholder="Иностранный идентификационный номер налогоплательщика"
                autocomplete="off"
                value="<?= ArrayHelper::Value( $this->Current, 'a6' ) ?>"
        >
    </div>
</div>

<div class="clearfix"></div>

<script>
    TDynamicCheckBox = function()
    {

    };

    TDynamicCheckBox.prototype.Initialize = function()
    {
        var self = this;
        //

        var $jsDynamicCheckboxes = $('[js-dynamic-checkbox]');

        $.each( $jsDynamicCheckboxes, function( i, elemi )
        {
            var $jsDynamicCheckbox = $( this );

            self.InitializeInstance( $jsDynamicCheckbox );
        });
    };

    TDynamicCheckBox.prototype.InitializeInstance = function( $jsDynamicCheckbox )
    {
        var self = this;
        //

        var ConfigStr = $jsDynamicCheckbox.attr( 'js-dynamic-checkbox' );
        var Config = JSON.parse( ConfigStr );

        //

        console.log( Config );

        //

        // CHECKED: "Y"
        // CONTROL: "[js-nalog-residence-block]"
        // TARGET: "#is_nalog_residence"
        // UNCHECKED: "N"

        //

        var $Target = $( Config.TARGET );
        var $Control = $( Config.CONTROL );

        //

        var ControlVisibledValue = $Control.attr( 'js-dynamic-checkbox-visibled-value' );

        //

        $jsDynamicCheckbox.on('change', function(e)
        {
            var $this = $(this);

            //

            var Value;

            if ( this.checked )
            {
                console.log('~CHECKED');

                Value = Config.CHECKED;
            }
            else
            {
                console.log('~UN_CHECKED');

                Value = Config.UNCHECKED;
            }

            console.log( 'Value', Value );

            //

            $Target.val( Value );

            //

            if ( ControlVisibledValue === Value )
            {
                $Control.show();
            }
            else
            {
                $Control.hide();
            }
        });
    };


    document.addEventListener("DOMContentLoaded", function(event)
    {
        var DynamicCheckBox = new TDynamicCheckBox();
        DynamicCheckBox.Initialize();

        //

        var ch_residence = $('#ch_residence');
        var residence_block = $('[js-residence-block]');
        var is_residence = $('#is_residence');

        var ch_tax = $('#ch_tax');
        var tax_block = $('[js-tax-block]');
        var is_tax = $('#is_tax');

        var jsAgreeDocumentsTax = $('[js-agree-documents-tax]');

        function UpdateCheckBoxes()
        {
            var IR = ch_residence.is(':checked');
            console.log( 'IR' );
            console.log( IR );

            var IT = ch_tax.is(':checked');
            console.log( 'IT' );
            console.log( IT );

            is_residence.val( (!ch_residence.is(':checked')) ? 'Y' : 'N' );
            residence_block.toggle(!ch_residence.is(':checked'));

            is_tax.val( (!ch_tax.is(':checked')) ? 'Y' : 'N' );
            tax_block.toggle(!ch_tax.is(':checked'));

            UpdateAgreeDocumentsTax();
        }

        function UpdateAgreeDocumentsTax()
        {
            var Visible = ( is_residence.val() == 'Y' || is_tax.val() == 'Y' );

            jsAgreeDocumentsTax.toggle( Visible );
        }

        UpdateCheckBoxes();

        ch_residence.on('change', function(e)
        {
            var $this = $(this);

            residence_block.toggle(!this.checked);

            is_residence.val( (!this.checked) ? 'Y' : 'N' );

            UpdateAgreeDocumentsTax();
        });

        var ch_residence_info = $('#ch_residence_info');
        var is_residence_info = $('#is_residence_info');

        var ch_terr = $('#ch_terr');

        ch_residence_info.on('change', function(e)
        {
            is_residence_info.val( (this.checked) ? 'Y' : 'N' );
        });

        ch_tax.on('change', function(e)
        {
            var $this = $(this);

            tax_block.toggle(!this.checked);

            is_tax.val( (!this.checked) ? 'Y' : 'N' );

            UpdateAgreeDocumentsTax();
        });

        var ch_tax_info = $('#ch_tax_info');
        var is_tax_info = $('#is_tax_info');

        var ch_terr = $('#ch_terr');

        ch_tax_info.on('change', function(e)
        {
            is_tax_info.val( (this.checked) ? 'Y' : 'N' );
        });

        jsAgreeFormSubmit = $('[js-agree-form-submit]');
        jsAgreeFormSubmitManager = $('[js-agree-form-submit-manager]');

        jsAgreeFormSubmit.on('click', function(e)
        {
            console.warn('jsAgreeFormSubmit ~ click');

            var is_residence_val = is_residence.val();
            var is_residence_info_val = is_residence_info.val();
            var ch_terr_val = ch_terr.is(':checked');

            //alert( ch_terr_val ? 'YY' : 'NN' );
            //e.preventDefault();

            console.log(is_residence_val);
            console.log(is_residence_info_val);

            //&& is_residence_info_val == 'N'

            if ( ch_terr_val == false )
            {
                console.log('Fuck OFF');
                e.preventDefault();

                window.location = jsAgreeFormSubmitManager.attr('href');
            }

            console.warn('jsAgreeFormSubmit ~ click ~ OMG');

            var Form = $('[js-agree-form]');

            if ( !Form.valid() )
            {
                console.warn('jsAgreeFormSubmit ~ click ~ INVALID');

                return;
            }

            e.preventDefault();

            var FormData = Form.serialize();

            console.log( 'FormData', FormData );

            var FormAction = Form.attr('action');

            var FormURI = Form.attr('js-ubu');

            var FormURL = FormURI + FormAction + '?' + FormData;

            console.log( 'FormURL', FormURL );

            window.location = FormURL;
        });
    });
</script>

<style>
    #adr .easy-autocomplete
    {
        width: 592px!important;
    }
</style>