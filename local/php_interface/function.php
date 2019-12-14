<?
//получаем текущую
function getCurCompany($usrId){
    $rsUsers = CUser::GetList(
        $by="timestamp_x",
        $order="desc",
        [
            'ID'=>$usrId
        ],
        [
            'SELECT'=> [
                'ID',
                'NAME',
                'UF_CUR_COMPANY'
            ]
        ]
    );
    if ($obj=$rsUsers->Fetch()){
        $idCompany = $obj['UF_CUR_COMPANY'];
    }
     return $idCompany;
}
?>