<?php
/**
 * Created by PhpStorm.
 * User: Яков
 * Date: 16.04.2017
 * Time: 2:12
 */

function debug_ingo_bool_fix_converter(&$value, $key){
    if(is_bool($value)){
        $value = ($value ? 1 : 0);
    }
}

function debug_ingo_bool_fix(Array $data)
{
    // Note the order of arguments and the & in front of $value

    array_walk_recursive($data, 'debug_ingo_bool_fix_converter');
    return $data;
}

?>
<div class="debug-info">

    <h1>Отладка имеющихся в коде классификаторов</h1>

    <table id="c-d-classificators" class="table table-striped table-bordered table-hover" >
        <tr class="info">
            <th class="info">##</th>
            <th class="info">Классификатор</th>
            <th class="info">Активен</th>
        </tr>

        <?
        $i = 0;
        ?>

        <? foreach ( $Classificators as $ClassificatorCode => $ClassificatorActive ) { ?>

            <?
            $i++;
            ?>

            <tr <?= ( $ClassificatorActive ) ? 'class="success"' : '' ?> >
                <td <?= ( $ClassificatorActive ) ? 'class="success"' : '' ?> ><?= $i ?></td>
                <td <?= ( $ClassificatorActive ) ? 'class="success"' : '' ?> ><?= $ClassificatorCode ?></td>
                <td <?= ( $ClassificatorActive ) ? 'class="success"' : '' ?> ><?= ( $ClassificatorActive ) ? 'Активен' : '-' ?></td>
            </tr>
        <? } ?>
    </table>
</div>

<div class="debug-info">

    <h1>Отладка имеющихся в коде классификаторов</h1>

    <h2>Имеющиеся классификаторы</h2>

    <div class="limited">
        <pre>
            <?= json_encode( $ClassificatorsAvaillabled, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ?>
        </pre>
    </div>

    <h2>Зависимости классификаторов</h2>

    <div class="limited">
        <pre>
            <?= json_encode( $ClassificatorsRelation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ?>
        </pre>
    </div>

    <h2>Модель документов</h2>

    <div class="limited">
        <pre>
            <?= json_encode( $DocumentsModel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ?>
        </pre>
    </div>
</div>

<style>
    .debug-info
    {
        border: black 1px solid;
        padding: 8px;
    }

    .debug-info .limited
    {
        max-height: 400px;
        overflow: scroll;
    }

    .debug-info table
    {
        width: 100%;
    }

    .debug-info table .info
    {
        background-color: #d9edf7;
    }

    .debug-info table .success
    {
        background-color: #dff0d8;
    }
</style>