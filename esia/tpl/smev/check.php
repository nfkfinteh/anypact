<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 22.10.2017
 * Time: 18:59
 */

?>

<div class='colwpp col-xs-12'>

    <div class='colwpl col-xs-12' style='display: none;' js-check-form-error>
        <div style='color: red; line-height: 60px;'>Данные не корректны! Проверьте правильность введенных данных!</div>
    </div>

    <div class='clearfix'></div>

    <div class='colwpl col-xs-3' js-check-form-block>

        <input type='button' class='submit btn btn_green btn-primary btn-md' value='Продолжить' js-check-form-submit />
    </div>

    <div class='colwpl col-xs-9' style='display: none;' js-check-form-loader>
        <div class='xxx1'>
            <div class="loader">
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg>
            </div>
        </div>
        <div class='xxx2' js-check-form-loader-text>Загрузка</div>
    </div>

    <div class='clearfix'></div>

    <div class='colwpl col-xs-12' js-check-form-progress style="display: none;">
        <h4>Пожалуйста, подождите!</h4>
        Данные проверяются с помощью системы межведомственного электронного взаимодействия (СМЭВ). Это может занять некоторое время!

        <div class="progress progress-m16">
            <div role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" js-slow-progress="" class="progress-bar progress-bar-success">

            </div>
        </div>
    </div>

    <div class='colwpl col-xs-12' js-check-form-success style="display: none;">
        <h4>Поздравляем, ваши данные прошли <strong>предварительную</strong> проверку!</h4>
        Для продолжения, пожалуйста, заполните оставшуюся часть анкеты!
    </div>
</div>

<script>
    function jsSlowProgressEngine($ProgressBar)
    {
        var self = this;

        self.ProgressBar = $ProgressBar;
    }

    jsSlowProgressEngine.prototype.ProgressBar = null;

    jsSlowProgressEngine.prototype.StyleValue = 'normal';

    jsSlowProgressEngine.prototype.StateValue = 'normal';

    jsSlowProgressEngine.prototype.MaximumValue = 100;

    jsSlowProgressEngine.prototype.PositionValue = 0;

    jsSlowProgressEngine.prototype.PercentValue = 0;

    jsSlowProgressEngine.prototype.Update = function()
    {
        var self = this;

        self.ProgressBar.removeAttr('class');

        self.ProgressBar.addClass('progress-bar');

        switch ( self.StateValue )
        {
            case 'normal': self.ProgressBar.addClass('progress-bar-info'); break;
            case 'error': self.ProgressBar.addClass('progress-bar-danger'); break;
            case 'success': self.ProgressBar.addClass('progress-bar-success'); break;
        }

        switch ( self.StyleValue )
        {
            case 'normal':
            {
                var Percent = ( self.MaximumValue > 0 )
                    ?  ( self.PositionValue / self.MaximumValue ) * 100
                    : 0;

                self.PercentValue = Percent;

                self.ProgressBar.css('width', Percent+'%');
            }
                break;

            case 'marque':
            {
                self.ProgressBar.css('width', '100%');

                self.ProgressBar.addClass('progress-bar-striped');
                self.ProgressBar.addClass('active');
            }
                break;
        }
    };

    jsSlowProgressEngine.prototype.Style = function(Value)
    {
        var self = this;

        self.StyleValue = Value;

        self.Update();
    };

    jsSlowProgressEngine.prototype.State = function(Value)
    {
        var self = this;

        self.StateValue = Value;

        self.Update();
    };

    jsSlowProgressEngine.prototype.Maximum = function(Value)
    {
        var self = this;

        self.MaximumValue = Value;

        self.Update();
    };

    jsSlowProgressEngine.prototype.Position = function(Value)
    {
        var self = this;

        self.PositionValue = Value;

        self.Update();
    };



    document.addEventListener("DOMContentLoaded", function(event)
    {
        console.warn('OMG');

        var $SlowProgress = $('[js-slow-progress]');

        var SlowProgressEngine = new jsSlowProgressEngine( $SlowProgress );

        var jsCheckFormSubmit = $('[js-check-form-submit]');

        var jsCheckFormLoader = $('[js-check-form-loader]');
        var jsCheckFormLoaderText = $('[js-check-form-loader-text]');

        var $Form = $('[js-agree-form]');

        var  jsSmevInput = $('[js-smev-input]');
        var jsCheckFormError = $('[js-check-form-error]');

        var jsCheckFormProgress = $('[js-check-form-progress]');

        var jsCheckFormSuccess = $('[js-check-form-success]');

        console.log('jsSmevInput');
        console.log(jsSmevInput);


        function LoaderShow(Message)
        {
            //jsCheckFormLoader.show();
            //jsCheckFormLoaderText.html(Message);
            jsCheckFormSubmit.attr('disabled', 'disabled');

            jsCheckFormProgress.show();

            $('[js-smev-hidden]').hide();
        }

        function LoaderHide()
        {
            jsCheckFormLoader.hide();
            jsCheckFormSubmit.removeAttr('disabled');
        }

        function MarkError()
        {
            console.warn('MarkError');

            jsSmevInput.addClass('error');

            jsCheckFormError.show();
        }

        function UnMarkError()
        {
            console.warn('UnMarkError');

            jsSmevInput.removeClass('error');

            jsCheckFormError.hide();
        }

        function ProcessFinalizeSuccess()
        {
            //valid
            $('[js-check-form-block]').hide();
            $('[js-smev-hidden]').show();

            jsCheckFormSuccess.show();

            LoaderHide();

            setTimeout(function() { jsCheckFormSuccess.hide(); }, 15000);
        }

        function ProcessFinalizeFailed()
        {
            //error
            LoaderHide();

            MarkError();
        }

        function SetProgress(Value)
        {
            if ( Value <= SlowProgressEngine.PositionValue )
            {
                return;
            }

            SlowProgressEngine.Position( Value );

            var SlowProgressPercent = SlowProgressEngine.PercentValue.toFixed(2);
            var SlowProgressPercentInt = SlowProgressEngine.PercentValue.toFixed();

            var SlowProgressPercentFloat = parseFloat( SlowProgressPercent );

            var SlowProgressText = ( SlowProgressPercentFloat < 20 ) ? SlowProgressPercentInt + ' %' : 'Выполнение ( ' + SlowProgressPercentInt + ' % )';

            $SlowProgress.text( SlowProgressText );
        }

        function ProcessFinalize(status)
        {
            //success
            //failed

            switch ( status )
            {
                case 'success': SlowProgressEngine.StateValue = 'success'; break;
                case 'failed': SlowProgressEngine.StateValue = 'error'; break;
            }

            SetProgress( SlowProgressEngine.MaximumValue );

            setTimeout(function()
            {
                jsCheckFormProgress.hide();

                switch ( status )
                {
                    case 'success': ProcessFinalizeSuccess(); break;
                    case 'failed': ProcessFinalizeFailed(); break;
                }
            },
            2222
            );
        }

        function ProcessCheckTimer(result)
        {
            SetProgress( result.TIME );

            if ( result.STATUS_HAVE === true )
            {
                if ( result.STATUS_VALID === true )
                {
                    ProcessFinalize('success');
                }
                else
                {
                    ProcessFinalize('failed');
                }

                return;
            }

            if ( result.CHECK_TIMER === true )
            {
                LoaderShow('Ожидание следующей проверки...');
                setTimeout(function() { SecondRequest(result.CHECK_ID); }, result.CHECK_TIMER_INTERVAL);
            }
        }

        function SecondRequest( ID )
        {
            var FormDataString = $Form.serialize() + '&ID=' + ID;

            console.log(FormDataString);

            console.log('AJAX');

            LoaderShow('Отправка запроса...');

            var FormAJAX = {
                url: '<?= $this->URL('smev_check_next') ?>',
                data: FormDataString,
                type: 'POST',
                dataType: 'json'
            };

            console.log( FormAJAX );

            $.ajax(FormAJAX)
                .done(function(result)
                {
                    LoaderShow('Ответ получен...');

                    console.log('DONE');

                    console.log(result);

                    if ( result.RESULT !== true )
                    {
                        ProcessFinalize('failed');
                    }
                    else
                    {
                        ProcessCheckTimer(result);
                    }
                })
                .fail(function( jqXHR, textStatus )
                {
                    console.log('FAIL');
                    console.log(textStatus);

                    ProcessFinalize('failed');
                })
                .always(function()
                {
                    console.log('ALWAYS');
                });
        }

        function FirstRequestCheck()
        {
            var Result = true;

            $.each( jsSmevInput, function(i, elemi)
            {
                var Value = $(this).val();

                Value = $.trim( Value );

                if ( Value === '' )
                {
                    Result = false;

                    return false;
                }
            });

            return Result;
        }

        function FirstRequest()
        {
            var FirstRequestCheckResult = FirstRequestCheck();

            if ( FirstRequestCheckResult )
            {
                SlowProgressEngine.StateValue = 'success';
                SlowProgressEngine.MaximumValue = 600;
                SlowProgressEngine.PositionValue = 0;
                SlowProgressEngine.Update();

                UnMarkError();
            }
            else
            {
                SlowProgressEngine.StateValue = 'error';
                SlowProgressEngine.MaximumValue = 600;
                SlowProgressEngine.PositionValue = 0;
                SlowProgressEngine.Update();

                MarkError();

                return false;
            }

            SetProgress(20);

            var FormDataString = $Form.serialize();

            console.log(FormDataString);

            console.log('AJAX');

            LoaderShow('Отправка запроса...');

            var FormAJAX = {
                url: '<?= $this->URL('smev_check_first') ?>',
                data: FormDataString,
                type: 'POST',
                dataType: 'json'
            };

            console.log( FormAJAX );

            $.ajax(FormAJAX)
                .done(function(result)
                {
                    LoaderShow('Ответ получен...');

                    console.log('DONE');

                    console.log(result);

                    if ( result.RESULT !== true )
                    {
                        ProcessFinalize('failed');
                    }
                    else
                    {
                        ProcessCheckTimer(result);
                    }
                })
                .fail(function( jqXHR, textStatus )
                {
                    console.log('FAIL');
                    console.log(textStatus);

                    ProcessFinalize('failed');
                })
                .always(function()
                {
                    console.log('ALWAYS');
                });
        }

        jsCheckFormSubmit.on('click', function(e)
        {
            e.preventDefault();

            console.warn('CLICK');

            FirstRequest();
        });
    });
</script>

<style>
    .progress-m16
    {
        margin-top: 16px;
    }

    .progress {
        height: 20px;
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #f5f5f5;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
    }
    .progress-bar {
        float: left;
        width: 0;
        height: 100%;
        font-size: 12px;
        line-height: 20px;
        color: #fff;
        text-align: center;
        background-color: #337ab7;
        -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
        -webkit-transition: width .6s ease;
        -o-transition: width .6s ease;
        transition: width .6s ease;
    }
    .progress-striped .progress-bar,
    .progress-bar-striped {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        -webkit-background-size: 40px 40px;
        background-size: 40px 40px;
    }
    .progress.active .progress-bar,
    .progress-bar.active {
        -webkit-animation: progress-bar-stripes 2s linear infinite;
        -o-animation: progress-bar-stripes 2s linear infinite;
        animation: progress-bar-stripes 2s linear infinite;
    }
    .progress-bar-success {
        background-color: #5cb85c;
    }
    .progress-striped .progress-bar-success {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
    }
    .progress-bar-info {
        background-color: #5bc0de;
    }
    .progress-striped .progress-bar-info {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
    }
    .progress-bar-warning {
        background-color: #f0ad4e;
    }
    .progress-striped .progress-bar-warning {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
    }
    .progress-bar-danger {
        background-color: #d9534f;
    }
    .progress-striped .progress-bar-danger {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
    }


    .btn[disabled],
    .btn[disabled]:hover
    {
        cursor: not-allowed;
        background-color: grey;
    }

    .xxx1
    {
        width: 50px;
        display: inline-block;
        /* margin-top: 7px; */
        position: absolute;
        top: 9px;
    }

    .xxx2
    {
        line-height: 8px;
        /* display: inline-block; */
        position: absolute;
        top: 24px;
        left: 50px;
    }

    .showbox {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 5%;
    }

    .loader {
        /*position: relative;*/
        /*margin: 0px auto;*/
        width: 40px;
    }

    .loader:before {
        /*content: '';*/
        /*display: block;*/
        /*padding-top: 100%;*/
    }

    .circular {
        -webkit-animation: rotate 2s linear infinite;
        animation: rotate 2s linear infinite;
        height: 100%;
        -webkit-transform-origin: center center;
        -ms-transform-origin: center center;
        transform-origin: center center;
        width: 100%;
        /*position: absolute;*/
        /*top: 0;*/
        /*bottom: 0;*/
        /*left: 0;*/
        /*right: 0;*/
        /*margin: auto;*/
    }

    .path {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0;
        -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
        animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
        stroke-linecap: round;
    }
    @-webkit-keyframes
    rotate {  100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
    @keyframes
    rotate {  100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
    @-webkit-keyframes
    dash {  0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0;
    }
        50% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -35;
        }
        100% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -124;
        }
    }
    @keyframes
    dash {  0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0;
    }
        50% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -35;
        }
        100% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -124;
        }
    }
    @-webkit-keyframes
    color {  100%, 0% {
        stroke: #d62d20;
    }
        40% {
            stroke: #0057e7;
        }
        66% {
            stroke: #008744;
        }
        80%, 90% {
            stroke: #ffa700;
        }
    }
    @keyframes
    color {  100%, 0% {
        stroke: #d62d20;
    }
        40% {
            stroke: #0057e7;
        }
        66% {
            stroke: #008744;
        }
        80%, 90% {
            stroke: #ffa700;
        }
    }
</style>
