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

//удаление временных фалов в директории
function deleteTmpFile($dir, $time){
    $scanFile = scandir($_SERVER['DOCUMENT_ROOT'].$dir);
    foreach ($scanFile as $file) {
        if ($file != "." && $file != "..") {
            //удаляем фал старше $time часов
            $urlFile = $_SERVER['DOCUMENT_ROOT'].$dir.$file;
            if (time() - filectime($urlFile) > $time * 60 * 60) {
                unlink($urlFile);
            }
        }
    }
}
?>