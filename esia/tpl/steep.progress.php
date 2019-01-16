<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 22:55
 */

/** @var \EsiaCore $this */

$SteepProgressModel = [
    'esia' => 'Данные',
    'docs' => 'Выбор',
    'docsagree' => 'Документы',
    'end' => 'Готово',
];

$SteepProgressAliases = [
    'agree' => 'esia',
];

?>

<div class="colwpp col-xs-12">

    <nav>
        <ol class="cd-multi-steps text-bottom count">

            <?
                $Action = ( !array_key_exists( $this->Action, $SteepProgressAliases ) ) ? $this->Action : $SteepProgressAliases[ $this->Action ];

                $have = array_key_exists( $Action, $SteepProgressModel );
                $current = false;

                foreach ( $SteepProgressModel as $SteepProgressModelID => $SteepProgressModelName )
                {
                    if ( !$have )
                    {
                        $kind = 'new';
                    }
                    else
                    {
                        if ( $SteepProgressModelID == $Action )
                        {
                            $current = true;

                            $kind = 'current';
                        }
                        else
                        {
                            if ( $current == false )
                            {
                                $kind = 'visited';
                            }
                            else
                            {
                                $kind = 'new';
                            }
                        }
                    }

                    $href = $this->URL( $SteepProgressModelID );

                    switch ( $kind )
                    {
                        case 'visited': $html = '<li class="visited"><a href="'.$href.'">'.$SteepProgressModelName.'</a></li>'; break;
                        case 'current': $html = '<li class="current"><em>'.$SteepProgressModelName.'</em></li>'; break;
                        case 'new': $html = '<li><em>'.$SteepProgressModelName.'</em></li>'; break;
                    }

                    echo $html . PHP_EOL;
                }
            ?>

        </ol>
    </nav>

</div>


