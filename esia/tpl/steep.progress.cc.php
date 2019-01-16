<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 25.04.2017
 * Time: 14:47
 */

/** @var \EsiaCore $this */

$SteepProgressModel = [];

if ( ArrayHelper::Value( $arResult, 'IS_SMEV' ) === 'Y' )
{
    $SteepProgressModel[ 'smev' ] = [
        'TITLE' => 'Анкета',
        'SUBTITLE' => 'Введите данные',
    ];
}
else
{
    $SteepProgressModel[ 'esia' ] = [
        'TITLE' => 'Анкета',
        'SUBTITLE' => 'Введите данные',
    ];
}

$SteepProgressModel[ 'docs' ] = [
    'TITLE' => 'Услуги',
    'SUBTITLE' => 'Выберите услуги',
];

$SteepProgressModel[ 'docsagree' ] = [
    'TITLE' => 'Документы',
    'SUBTITLE' => 'Подписание документов',
];

$SteepProgressModel[ 'end' ] = [
    'TITLE' => 'Готово',
    'SUBTITLE' => 'Завершение процедуры',
];

if ( ArrayHelper::Value( $arResult, 'IS_SMEV' ) === 'Y' )
{
    $SteepProgressAliases = [
        'agree' => 'smev',
    ];
}
else
{
    $SteepProgressAliases = [
        'agree' => 'esia',
    ];
}

?>

<div class="colwpp col-xs-12" style="margin-bottom: 40px">
    <div class="multi-step numbered">
        <ol>
            <?
            $Action = ( !array_key_exists( $this->Action, $SteepProgressAliases ) ) ? $this->Action : $SteepProgressAliases[ $this->Action ];

            $have = array_key_exists( $Action, $SteepProgressModel );
            $current = false;

            foreach ( $SteepProgressModel as $SteepProgressModelID => $SteepProgressModelRec )
            {
                if (!$have)
                {
                    $kind = 'new';
                }
                else
                {
                    if ($SteepProgressModelID == $Action)
                    {
                        $current = true;

                        $kind = 'current';
                    }
                    else
                    {
                        if ($current == false)
                        {
                            $kind = 'visited';
                        }
                        else
                        {
                            $kind = 'new';
                        }
                    }
                }

                $href = ( $Action != 'end' ) ? $this->URL($SteepProgressModelID) : null;

                $SteepProgressModelRecTitle = ArrayHelper::Value( $SteepProgressModelRec, 'TITLE' );
                $SteepProgressModelRecSubTitle = ArrayHelper::Value( $SteepProgressModelRec, 'SUBTITLE' );

                ob_start();

                switch ( $kind )
                {
                    case 'visited':
                        ?>
                            <li class="current">

                                <? if ( $href ) { ?>
                                    <a href="<?= $href ?>">
                                <? } ?>

                                    <div class="wrap">
                                        <p class="title"><?= $SteepProgressModelRecTitle ?></p>
                                        <p class="subtitle"><?= $SteepProgressModelRecSubTitle ?></p>
                                    </div>

                                <? if ( $href ) { ?>
                                    </a>
                                <? } ?>
                            </li>
                        <?
                    break;

                    case 'new':
                        ?>
                            <li>
                                <div class="wrap">
                                    <p class="title"><?= $SteepProgressModelRecTitle ?></p>
                                    <p class="subtitle"><?= $SteepProgressModelRecSubTitle ?></p>
                                </div>
                            </li>
                        <?
                    break;

                    case 'current':
                        ?>
                            <li class="current">
                                <div class="wrap">
                                    <p class="title"><?= $SteepProgressModelRecTitle ?></p>
                                    <p class="subtitle"><?= $SteepProgressModelRecSubTitle ?></p>
                                </div>
                            </li>
                        <?
                    break;
                }

                $html = ob_get_contents();

                ob_end_clean();

                echo $html;
            }
            ?>
        </ol>
    </div>
</div>

