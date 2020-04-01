<form action="<?=$arResult["FORM_ACTION"]?>" class="form-filter__user">
    <label>Имя /  Название компании</label>
        <input type="text" name="NAME" value="<?=$arResult['POST']['NAME']?>" maxlength="50" class="filter-key" placeholder="Введите имя / Название компании" />
    <label>Город</label>
        <input type="text" name="PERSONAL_CITY" value="<?=$arResult['POST']['PERSONAL_CITY']?>" maxlength="50" class="filter-key" placeholder="Введите город" />
        <input type="hidden" name="ACTION" value="search_user" />
        <input type="hidden" name="TYPE" value="<?=$arParams['TYPE_FILTER']?>" />
        <input type="submit" class="btn btn-nfk" value="Поиск" style="margin-top: 15px;"/>
</form>
