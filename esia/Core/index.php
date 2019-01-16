<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 1:52
 */

function ShutDownError()
{
    $LE = error_get_last();

    //var_dump( $LE );

    return false;
}

if ( isset( $_REQUEST['ShutDownError'] ) && $_REQUEST['ShutDownError'] == 'Y' )
{
    register_shutdown_function( 'ShutDownError' );
}

require_once 'Functions.php';

class EsiaCore
{
    public $DIRRECTORY_ESIA = '';
    public $DIRRECTORY_ESIA_LOG = '';
    public $DIRRECTORY_ESIA_SERT = '';
    public $DIRRECTORY_ESIA_TEMP = '';
    public $DIRRECTORY_ESIA_TPL = '';
    public $DIRRECTORY_ESIA_CORE = '';
    public $DIRRECTORY_ESIA_CORE_ACTIONS = '';

    public $Action;
    public $Code;

    public $ID = null;
    public $Preview = [];
    public $Detail = [];

    public $Current = [];

    public $CurrentModel = [
        'home' => 'open',
        'esia' => 'agree',
        'docs' => 'docsagree',
    ];

    public $Exists = false;

    public $Settings = [];

    public $DB;

    public $Title = 'Открытие счета через Госуслуги';

    public function __construct( $Code = null )
    {
        $this->Dirrectories();

        $this->AutoLoadRegister();

        $this->LoadSettings();
        $this->Connect();

        if ( $Code === null )
        {
            if ( defined( 'ESIA_CORE_ACTION_FORCE' ) )
            {
                $this->Action = ESIA_CORE_ACTION_FORCE;
                $this->Code = '';
            }
            else
            {
                $this->Action = $this->Request( 'Stage', 'home' );
                $this->Code = $this->Request( 'StageCode' );
            }

            $this->Action = strtolower( $this->Action );

            $Force = false;
        }
        else
        {
            $this->Code = mysqli_real_escape_string( $this->DB, $Code );
            $this->Action = null;

            $Force = false;
        }

        if ( empty( $this->Code ) )
        {
            $this->Code = RandomFx::UnicalSHA1Strong( 'random' );
        }

        $this->Exists = $this->Load();

        if ( $this->Exists )
        {
            if ( $Force === false )
            {
                $this->CheckRedirrect();
            }


            $this->LoadCurrent();
        }

        if ( $Force === false )
        {
            $this->PushStage();

            $this->Stage();

            $this->Save();
        }
    }

    public function CheckRedirrect()
    {
        $Access = true;

        if ( $this->Action === 'omg' )
        {
            $Key = ArrayHelper::Value( $_REQUEST, 'KEY' );

//            if ( $Key !== '6e66e41deea77fbdc626c6a5d31e05d389370300' )
//            {
//                $Access = false;
//            }
        }
        else
        {
            $arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

            $X = ArrayHelper::Value( $arResult, 'end' );

            if ( ( $X == 'happy' ) && ( ( $this->Action != 'end' ) || ( $this->Action != 'omg' ) ) )
            {
                $Access = false;
            }
        }

        if ( $Access === false )
        {
            header('Location: ' . HTTPHelper::GenerateUniversalBaseURL());

            exit;
        }
    }

    protected function Dirrectories()
    {
        $this->DIRRECTORY_ESIA = XDynamicConfig::$SitePath . 'esia' . DIRECTORY_SEPARATOR;

        $this->DIRRECTORY_ESIA_LOG = $this->DIRRECTORY_ESIA . 'log' . DIRECTORY_SEPARATOR;
        $this->DIRRECTORY_ESIA_SERT = $this->DIRRECTORY_ESIA . 'sert' . DIRECTORY_SEPARATOR;
        $this->DIRRECTORY_ESIA_TEMP = $this->DIRRECTORY_ESIA . 'temp' . DIRECTORY_SEPARATOR;
        $this->DIRRECTORY_ESIA_TPL = $this->DIRRECTORY_ESIA . 'tpl' . DIRECTORY_SEPARATOR;
        $this->DIRRECTORY_ESIA_CORE = $this->DIRRECTORY_ESIA . 'Core' . DIRECTORY_SEPARATOR;
        $this->DIRRECTORY_ESIA_CORE_ACTIONS = $this->DIRRECTORY_ESIA_CORE . 'Actions' . DIRECTORY_SEPARATOR;
    }

    protected function AutoLoadRegister()
    {
        if (function_exists('__autoload'))
        {
            //    Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }

        spl_autoload_register( [$this, 'AutoLoad'] );
    }

    public function AutoLoad( $Class )
    {
        $FileName = $this->DIRRECTORY_ESIA . $Class . '.class.php';

        if ( file_exists( $FileName ) && is_readable( $FileName ) )
        {
            require_once $FileName;

            return true;
        }

        return false;
    }

    public function Request( $Name, $Default = null, $Safe = true )
    {
        $Result = ArrayHelper::Value( $_REQUEST, $Name, $Default );

        if ( $Safe === true )
        {
            $Result = mysqli_real_escape_string( $this->DB, $Result );
        }

        return $Result;
    }

    public function LoadSettings()
    {
        require XDynamicConfig::$CorePath . 'config/config.inc.php';

        $this->Settings['TYPE'] = $database_type;
        $this->Settings['HOST'] = $database_server;
        $this->Settings['USER'] = $database_user;
        $this->Settings['PASS'] = $database_password;
        $this->Settings['CS'] = $database_connection_charset;
        $this->Settings['NAME'] = $dbase;
    }

    public function Connect()
    {
        $this->DB = mysqli_connect($this->Settings['HOST'], $this->Settings['USER'], $this->Settings['PASS'], $this->Settings['NAME']);

        mysqli_set_charset($this->DB,$this->Settings['CS']);
        mysqli_query($this->DB, "SET NAMES '" . strtoupper( $this->Settings['CS'] ) . "'");
    }

    public function Load()
    {
        $Result = false;

        $SQL = "SELECT * FROM `open_account` WHERE `code` = '{$this->Code}' LIMIT 1";

        $arFields = $this->DB2Array( $SQL, 'SELECT' );

        $Fields = ( is_array($arFields) ) ? array_shift( $arFields ) : false ;

        if ( is_array( $Fields ) )
        {
            $this->ID = $Fields['id'];

            $this->Preview = $this->Decode( $Fields['preview'] );
            $this->Detail = $this->Decode( $Fields['detail'] );

            $Result = true;
        }

        return $Result;
    }

    public function LoadCurrent()
    {
        $Action = $this->Action;

        if ( array_key_exists( $Action, $this->CurrentModel ) )
        {
            $Action = $this->CurrentModel[ $Action ];
        }

        $this->Current = ArrayHelper::Value( $this->Preview, $Action, [] );
    }

    protected function Decode( $Value )
    {
        $Result = base64_decode( $Value );

        $Result = unserialize( $Result );

        if ( !is_array( $Result ) )
        {
            $Result = [];
        }

        return $Result;
    }

    public function Save()
    {
        $Fields = [
            'code' => [
                'TYPE' => 'string',
                'KIND' => [ 'INSERT' ],
                'VALUE' => $this->Code,
            ],

            'preview' => [
                'TYPE' => 'string',
                'KIND' => [ 'INSERT', 'UPDATE' ],
                'VALUE' => $this->Encode( $this->Preview ),
                'NULL' => true,
            ],

            'detail' => [
                'TYPE' => 'string',
                'KIND' => [ 'INSERT', 'UPDATE' ],
                'VALUE' => $this->Encode( $this->Detail ),
                'NULL' => true,
            ],
        ];

        $this->DB_AddOrUpdate( 'open_account', $Fields );
    }

    protected function Encode( $Value )
    {
        $Result = serialize( $Value );

        $Result = base64_encode( $Result );

        return $Result;
    }

    public function Merge( &$Array, $Key, $Value )
    {
        $Exists = ArrayHelper::Value( $Array, $Key );

        $Data = ( is_array( $Exists ) ) ? array_merge( $Exists, $Value ) : $Value;

        $Array[ $Key ] = $Data;
    }

    public function PushStage()
    {
        $this->Merge( $this->Preview, $this->Action, $_REQUEST );
    }

    public function Stage()
    {
        $Script = $this->DIRRECTORY_ESIA_CORE_ACTIONS . $this->Action . '.php';

        if ( file_exists( $Script ) && is_readable( $Script ) )
        {
            require_once $Script;
        }
    }

    public function URL( $Stage )
    {
        $Result = '/esia/' . $this->Code . '/' . $Stage;

        return $Result;
    }

    public function DB2Array($SQL, $Mode = '')
    {
        $Res = mysqli_query( $this->DB, $SQL );

        $GrandResult = $Res;

        if ( is_object( $Res ) )
        {
            //$Result =  $Res->fetch_all(MYSQLI_ASSOC);

            $Result = [];

            while ( $row = $Res->fetch_assoc() )
            {
                $Result[] = $row;
            }

            switch ( $Mode )
            {
                case 'SELECT':
                    $GrandResult = ( count( $Result ) > 0 ) ? $Result : false;
                    break;

                default: $GrandResult = ( count( $Result ) > 0 ) ? $Result : true;
            }
        }

        return $GrandResult;
    }

    public function DB_Gen_INSERT( $Fields )
    {
        $Result = '';

        $Names = [];
        $Values = [];

        foreach ( $Fields as $FieldName => $FieldConfig )
        {
            $Type = ArrayHelper::Value( $FieldConfig, 'TYPE' );

            if ( empty( $Type ) )
            {
                continue;
            }

            $Value = ArrayHelper::Value( $FieldConfig, 'VALUE' );

            $NULL = ArrayHelper::Value( $FieldConfig, 'NULL' );

            if ( $NULL === true )
            {
                if ( empty( $Value ) && $Value !== 0 )
                {
                    $Value = 'NULL';
                }
            }

            if ( $Value != 'NULL' )
            {
                switch ( $Type )
                {
                    case 'string': $Value = '\'' . $Value . '\''; break;
                }
            }

            $Names[] = '`' . $FieldName . '`';
            $Values[] = $Value;
        }

        if ( count ( $Names ) > 0 && count ( $Names ) == count ( $Values ) )
        {
            $Result = '( ' . implode(', ', $Names ) . ' ) VALUES ( ' . implode( ', ', $Values ) . ' )';
        }

        return $Result;
    }

    public function DB_Gen_UPDATE( $Fields )
    {
        $Result = '';

        $Res = [];

        foreach ( $Fields as $FieldName => $FieldConfig )
        {
            $Type = ArrayHelper::Value( $FieldConfig, 'TYPE' );

            if ( empty( $Type ) )
            {
                continue;
            }

            $Value = ArrayHelper::Value( $FieldConfig, 'VALUE' );

            $NULL = ArrayHelper::Value( $FieldConfig, 'NULL' );

            if ( $NULL === true )
            {
                if ( empty( $Value ) && $Value !== 0 )
                {
                    $Value = 'NULL';
                }
            }

            if ( $Value != 'NULL' )
            {
                switch ( $Type )
                {
                    case 'string': $Value = '\'' . $Value . '\''; break;
                }
            }

            $Res[] = '`' . $FieldName . '` = ' . $Value ;
        }

        if ( count( $Res ) > 0 )
        {
            $Result = implode( ', ', $Res );
        }

        return $Result;
    }

    public function DB_AddOrUpdate( $Table, $Fields )
    {
        $Insert = [];
        $Update = [];

        foreach ( $Fields as $FieldName => $FieldConfig )
        {
            $Kind = ArrayHelper::Value( $FieldConfig, 'KIND' );

            if ( empty( $Kind ) || !is_array($Kind) )
            {
                continue;
            }

            if ( in_array( 'INSERT', $Kind ) )
            {
                $Insert[ $FieldName ] = &$Fields[$FieldName];
            }

            if ( in_array( 'UPDATE', $Kind ) )
            {
                $Update[ $FieldName ] = &$Fields[$FieldName];
            }
        }

        $SQL = 'INSERT INTO `' . $Table . '` ' . $this->DB_Gen_INSERT( $Insert ) . ' ON DUPLICATE KEY UPDATE ' . $this->DB_Gen_UPDATE( $Update );

        $Res = mysqli_query( $this->DB, $SQL );

        $Result = mysqli_insert_id( $this->DB );

        $this->ID = $Result;

        return $Result;
    }

}

if ( ! ( defined( 'ESIA_CORE_EXTERNAL' ) && ESIA_CORE_EXTERNAL == true ) )
{
    $EsiaCore = new \EsiaCore();
}