<div class="city-choose">
    <div class="container">
        <button class="city-choose-btn-close">Закрыть&nbsp;&nbsp;&nbsp;х</button>
        <h2>Выберите город</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <?foreach ($arResult['CITY'] as $city):?>
                        <div class="col-6 col-sm-4 col-md-6 col-xl-4">
                            <button class="city-choose-btn-city <?if($city['PROPERTY_BOLD_VALUE']=='Y'):?>font-weight-bold<?endif?>"><?=$city['NAME']?></button>
                        </div>
                    <?endforeach?>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <span class="city-choose-form-header">Или введите в поле</span>
            <form class="sity-submit">
                <div class="row">
                    <div class="col-md-6"><input type="text" class="sity-submit_input" placeholder="Введите город (например &quot;Санкт-Петербург&quot;)"></div>
                    <div class="col-md-6"><button class="btn btn-nfk-invert city-choose-btn-choose">Выбрать</button></div>
                </div>
            </form>
    </div>
</div>