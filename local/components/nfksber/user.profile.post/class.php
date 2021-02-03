<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CUserProfilePost extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "USER_ID" => intval($arParams["USER_ID"]),
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    public function getUserData($user_id)
    {
        if(empty(intval($user_id)))
            return array();

        if(!empty($this -> arUsers[$user_id])){
            return $this -> arUsers[$user_id];
        }else{
            $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_PHOTO", "EMAIL")));
            if($arUser = $res->Fetch()) {
                $this -> arUsers[$user_id] = $arUser;
                return $arUser;
            }
        }

        return array();
    }

    function getLike($entity_id, $entity_type){
        if(empty(intval($entity_id)) && empty(intval($entity_type)))
            return array();

        $arResult = array();
        
        $entity_data_class = self::GetEntityDataClass(USER_LIKE_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_ENTITY_ID" => $entity_id, "UF_ENTITY_TYPE" => $entity_type)
        ));
        while($arLike = $rsData->Fetch()){
            $arResult[] = $arLike['UF_USER_ID'];
        }

        return $arResult;
    }
    
    function getPostComment($post_id){
        if(empty(intval($post_id)))
            return array();

        $arResult = array();

        $nav = new \Bitrix\Main\UI\PageNavigation("nav-comment-".$post_id);
        $nav->allowAllRecords(true)
            ->setPageSize(3)
            ->initFromUri();
        
        $entity_data_class = self::GetEntityDataClass(USER_COMMENT_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
            "filter" => array("UF_ENTITY_ID" => $post_id)
        ));
        $nav->setRecordCount($rsData->getCount());
        while($arComment = $rsData->Fetch()){

            $arResult['ITEMS'][] = array(
                "ID" => $arComment['ID'],
                "DATE_CREATE" => $arComment['UF_CREATE_DATE'],
                "TEXT" => $arComment['UF_COMMENT_TEXT'],
                "COMMENT_ID" => $arComment['UF_COMMENT_ID'],
                "AUTHOR" => $this -> getUserData($arComment['UF_AUTHOR_ID']),
                "LIKES" => $this -> getLike($arComment['ID'], USER_COMMENT_HLB_ID),
                "FILES" => $this -> getFiles($arComment['ID'], "HLB_".USER_COMMENT_HLB_ID),
                "ANSWER_TO" => $this -> getUserData($arComment['UF_ANSWER_TO'])
            );
            
        }

        $arResult['COMMENT_TOTAL_PAGE'] = $nav->getPageCount();
        $arResult['COMMENT_TOTAL_COUNT'] = $rsData->getCount();
        
        if(empty($arResult['COMMENT_TOTAL_COUNT']))
            $arResult['COMMENT_TOTAL_COUNT'] = 0;
        
        return $arResult;
    }

    function getUserPosts($user_id){
        if(empty(intval($user_id)))
            return array();

        $arResult = array();

        $nav = new \Bitrix\Main\UI\PageNavigation("nav-post");
        $nav->allowAllRecords(true)
            ->setPageSize(5)
            ->initFromUri();
        
        $entity_data_class = self::GetEntityDataClass(USER_POST_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "DESC"),
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
            "filter" => array("UF_USER_ID" => $user_id)
        ));

        $nav->setRecordCount($rsData->getCount());

        while($arPost = $rsData->Fetch()){

            $arResult[] = array(
                "ID" => $arPost['ID'],
                "DATE_CREATE" => $arPost['UF_CREATE_DATE'],
                "TEXT" => $arPost['UF_POST_TEXT'],
                "ORIGINAL_ID" => $arPost['UF_POST_ID'],
                "AUTHOR" => $this -> getUserData($arPost['UF_AUTHOR_ID']),
                "COMMENTS" => $this -> getPostComment($arPost['ID']),
                "LIKES" => $this -> getLike($arPost['ID'], USER_POST_HLB_ID),
                "FILES" => $this -> getFiles($arPost['ID'], "HLB_".USER_POST_HLB_ID)
            );
           
        }
            
        $this -> arResult['TOTAL_PAGE'] = $nav->getPageCount();

        if($this -> arResult['PAGE'] > $this -> arResult['TOTAL_PAGE'])
            return array();
        
        return $arResult;
    }

    function getPostById($post_id){
        if(empty(intval($post_id)))
            return array();

        $arResult = array();
        
        $entity_data_class = self::GetEntityDataClass(USER_POST_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "DESC"),
            "filter" => array("ID" => $post_id)
        ));
        while($arPost = $rsData->Fetch()){
            $arResult[0] = array(
                "ID" => $arPost['ID'],
                "DATE_CREATE" => $arPost['UF_CREATE_DATE'],
                "TEXT" => $arPost['UF_POST_TEXT'],
                "ORIGINAL_ID" => $arPost['UF_POST_ID'],
                "AUTHOR" => $this -> getUserData($arPost['UF_AUTHOR_ID']),
                "COMMENTS" => $this -> getPostComment($arPost['ID']),
                "LIKES" => $this -> getLike($arPost['ID'], USER_POST_HLB_ID),
                "FILES" => $this -> getFiles($arPost['ID'], "HLB_".USER_POST_HLB_ID)
            );
        }
        
        return $arResult;
    }

    private function getBlackList(){
        $entity_data_class = self::GetEntityDataClass(15);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $this->arResult["CURRENT_USER"]['ID'], "UF_USER_B" => $this->arResult["USER"]['ID']),
                array("UF_USER_A" => $this->arResult["USER"]['ID'], "UF_USER_B" => $this->arResult["CURRENT_USER"]['ID']),
            ))
        ));
        while($arData = $rsData->Fetch()){
            if($arData['UF_USER_A'] == $this->arResult["CURRENT_USER"]['ID']){
                $result['CLOSE'] = true;
            }elseif($arData['UF_USER_B'] == $this->arResult["CURRENT_USER"]['ID']){
                $result['CLOSED'] = true;
            }
        }

        if(empty($result)){
            $result = [];
        }

        return $result;
    }

    private function getFrends(){
        $entity_data_class = self::GetEntityDataClass(14);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_A", "UF_USER_B", "UF_ACCEPT"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $this->arResult["USER"]['ID'], "UF_USER_B" => $this->arResult["CURRENT_USER"]['ID'], "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
                array("UF_USER_A" => $this->arResult["CURRENT_USER"]['ID'], "UF_USER_B" => $this->arResult["USER"]['ID'], "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
            ))
        ));
        if($arData = $rsData->Fetch()){
            return true;
        }

        return false;
    }

    function addPost($arFields){
        if(!is_array($arFields) || (empty(trim($arFields['TEXT'])) && empty($arFields['FILE_ID'])) || $this->arResult['CURRENT_USER']['ID'] != $this->arResult['USER']['ID'])
            return array();

        $entity_data_class = self::GetEntityDataClass(USER_POST_HLB_ID);
        $date = date("d.m.Y H:i:s");
        $result = $entity_data_class::add(array(
            "UF_AUTHOR_ID" => $this->arResult["CURRENT_USER"]['ID'],
            "UF_POST_TEXT" => trim($arFields['TEXT']),
            "UF_POST_ID" => $arFields['ORIGIN_ID'],
            "UF_CREATE_DATE" => $date,
            "UF_USER_ID" => $this->arResult["USER"]['ID'],
        ));
        $post_id = $result->getId();
        if($post_id){
            if(!empty($arFields['FILE_ID'])){
                if(!is_array($arFields['FILE_ID']))
                    $arFields['FILE_ID'] = [$arFields['FILE_ID']];
                $this -> attachFile($arFields['FILE_ID'], $post_id, 'HLB_'.USER_POST_HLB_ID);
            }
            return array(
                "ID" => $post_id,
                "DATE_CREATE" => $date,
                "TEXT" => trim($arFields['TEXT']),
                "AUTHOR" => $this->arResult["CURRENT_USER"],
                "LIKES" => array(),
                "COMMENTS" => array("COMMENT_TOTAL_COUNT" => 0),
                "FILES" => $this -> getFiles($post_id, "HLB_".USER_POST_HLB_ID)
            );
        }

        return array();
    }

    function addComment($post_id, $arFields){
        if(empty(intval($post_id)) || !is_array($arFields) || (empty(trim($arFields['TEXT'])) && empty($arFields['FILE_ID'])) || (!$this->arResult["FRIEND"] && $this->arResult["CURRENT_USER"]['ID'] != $this->arResult["USER"]['ID']))
            return array();

        $entity_data_class = self::GetEntityDataClass(USER_COMMENT_HLB_ID);
        $date = date("d.m.Y H:i:s");
        $result = $entity_data_class::add(array(
            "UF_AUTHOR_ID" => $this -> arResult['CURRENT_USER']['ID'],
            "UF_COMMENT_TEXT" => trim($arFields['TEXT']),
            "UF_COMMENT_ID" => $arFields['ORIGIN_ID'],
            "UF_CREATE_DATE" => $date,
            "UF_ENTITY_ID" => $post_id,
            "UF_ANSWER_TO" => $arFields['ANSWER_TO'],
        ));
        $comment_id = $result->getId();
        if($comment_id){
            if(!empty($arFields['FILE_ID'])){
                if(!is_array($arFields['FILE_ID']))
                    $arFields['FILE_ID'] = [$arFields['FILE_ID']];
                $this -> attachFile($arFields['FILE_ID'], $comment_id, 'HLB_'.USER_COMMENT_HLB_ID);
            }
            $this -> sendEmail($post_id, trim($arFields['TEXT']));
            return array(
                "ID" => $comment_id,
                "DATE_CREATE" => $date,
                "TEXT" => trim($arFields['TEXT']),
                "AUTHOR" => $this -> arResult['CURRENT_USER'],
                "LIKES" => array(),
                "FILES" => $this -> getFiles($comment_id, "HLB_".USER_COMMENT_HLB_ID),
                "ANSWER_TO" => $this -> getUserData($arFields['ANSWER_TO'])
            );
        }
        return array();
    }

    function likeUnlike($entity_id, $entity_type){
        if(empty(intval($entity_id)) || empty(intval($entity_type)))
            return false;
        
        $entity_data_class = self::GetEntityDataClass(USER_LIKE_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_ENTITY_ID" => $entity_id, "UF_ENTITY_TYPE" => $entity_type, "UF_USER_ID" => $this -> arResult['CURRENT_USER']['ID'])
        ));
        if($like = $rsData->Fetch()){
            $entity_data_class::Delete($like['ID']);
            return 'unlike';
        }else{
            $result = $entity_data_class::add(array(
                "UF_ENTITY_ID" => $entity_id,
                "UF_ENTITY_TYPE" => $entity_type,
                "UF_USER_ID" => $this -> arResult['CURRENT_USER']['ID'],
            ));
            $like_id = $result->getId();
            if($like_id){
                return 'like';
            }
        }

        return false;
    }

    private function deletePost($post_id){
        if(empty(intval($post_id)))
            return false;
        
        $entity_data_class = self::GetEntityDataClass(USER_POST_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_AUTHOR_ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("ID" => $post_id)
        ));
        if($post = $rsData->Fetch()){
            if($this->arResult['CURRENT_USER']['ID'] == $this->arResult['USER']['ID'] || $this->arResult['CURRENT_USER']['ID'] == $post['UF_AUTHOR_ID']){
                $entity_data_class::Delete($post['ID']);
                return true;
            }
        }
        return false;
    }

    private function deleteComment($comment_id){
        if(empty(intval($comment_id)))
            return false;
        
        $entity_data_class = self::GetEntityDataClass(USER_COMMENT_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_AUTHOR_ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("ID" => $comment_id)
        ));
        if($comment = $rsData->Fetch()){
            if($this->arResult['CURRENT_USER']['ID'] == $this->arResult['USER']['ID'] || $this->arResult['CURRENT_USER']['ID'] == $comment['UF_AUTHOR_ID']){
                $entity_data_class::Delete($comment['ID']);
                return true;
            }
        }

        return false;
    }

    private function addFile($file_type){
        if(!is_array($_FILES) || empty($_FILES) || empty($file_type))
            return false;

        $entity_data_class = self::GetEntityDataClass(USER_FILE_HLB_ID);
        foreach($_FILES as $file){
            $rsData = $entity_data_class::add(array(
                "UF_USER_ID" => $this->arResult['CURRENT_USER']['ID'],
                "UF_FILE_TYPE" => $file_type,
                "UF_FILE" => $file,
                "UF_CREATE_DATE" => date("d.m.Y H:i:s")
            ));
            $arFiles[] = $rsData->getId();
        }
        if($arFiles){
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_FILE"),
                "order" => array("ID" => "ASC"),
                "filter" => array("ID" => $arFiles)
            ));
            while($arData = $rsData->Fetch()){
                $arResult[] = array("ID" => $arData['ID'], "FILE" => CFile::GetFileArray($arData['UF_FILE']));
            }
            if($arResult){
                return $arResult;
            }
        }
        return false;
    }

    private function deleteFile($id){
        if(empty(intval($id)))
            return false;
        $entity_data_class = self::GetEntityDataClass(USER_FILE_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("ID" => $id, "UF_USER_ID" => $this->arResult['CURRENT_USER']['ID'])
        ));
        if($file = $rsData->Fetch()){
            $entity_data_class::Delete($file['ID']);
            return true;
        }
        return false;
    }

    private function attachFile($arFiles, $entity_id, $entity_type){
        if(!is_array($arFiles) || empty($arFiles) || empty(intval($entity_id)) || empty($entity_type))
            return false;

        $entity_data_class = self::GetEntityDataClass(USER_FILE_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("ID" => $arFiles, "UF_USER_ID" => $this->arResult['CURRENT_USER']['ID'])
        ));
        $i = 1;
        while($file = $rsData->Fetch()){
            if($i <= 10){
                $entity_data_class::update($file['ID'], array(
                    "UF_ENTITY_ID" => $entity_id,
                    "UF_ENTITY_TYPE" => $entity_type,
                ));
                $i++;
            }else{
                break;
            }
        }
        return true;
    }

    private function getFiles($entity_id, $entity_type){
        if(empty(intval($entity_id)) || empty($entity_type))
            return array();
        
        $entity_data_class = self::GetEntityDataClass(USER_FILE_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_FILE_TYPE", "UF_FILE"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ENTITY_ID" => $entity_id, "UF_ENTITY_TYPE" => $entity_type, "UF_FILE_TYPE" => array("img", "doc"))
        ));
        while($file = $rsData->Fetch()){
            if($file["UF_FILE_TYPE"] == 'img')
                $arResult['IMAGES'][] = $file["UF_FILE"];
            elseif($file["UF_FILE_TYPE"] == 'doc')
                $arResult['DOCS'][] = $file["UF_FILE"];
        }

        if($arResult)
            return $arResult;

        return array();
    }

    private function sendEmail($post_id, $comment_text){
        if($this->arResult["CURRENT_USER"]['ID'] != $this->arResult["USER"]['ID']){
            $arEventFields = array(
                "EMAIL" => $this->arResult["USER"]['EMAIL'],
                "AUTHOR_ID" => $this->arResult["USER"]['ID'],
                "POST_ID" => $post_id,
                "USER_FIO" => $this->arResult["CURRENT_USER"]['LAST_NAME']." ".$this->arResult["CURRENT_USER"]['NAME']." ".$this->arResult["CURRENT_USER"]['SECOND_NAME'],
                "USER_ID" => $this->arResult["CURRENT_USER"]['ID'],
                "COMMENT_TEXT" => $comment_text
            );
            CEvent::Send("POST_ADD_COMMENT", SITE_ID, $arEventFields);
        }
    }

    public function executeComponent()
    {
        if(CModule::IncludeModule("highloadblock")){

            if(!empty(($this->request->get('nav-post'))))
                $this->arResult["PAGE"] = intval(substr($_REQUEST['nav-post'], 5));
            else
                $this->arResult["PAGE"] = 1;

            global $USER;
            $this->arResult["CURRENT_USER"] = $this -> getUserData($USER -> GetID());
            $this->arResult["USER"] = $this -> getUserData($this -> arParams['USER_ID']);
            $this->arResult["BLACKLIST"] = $this->getBlackList();
            $this->arResult["FRIEND"] = $this->getFrends();
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax){
                if($this->request->get($this -> arParams['ACTION_VARIABLE']) == "newPost"){
                    $this -> arResult['POSTS'][] = $this -> addPost($_REQUEST['DATA']);
                    $this -> arResult['UPLOAD_POST'] = "Y";
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "newComment"){
                    $this -> arResult['COMMENTS']['ITEMS'][] = $this -> addComment($_REQUEST['post_id'], $_REQUEST['DATA']);
                    $this -> arResult['UPLOAD_COMMENT'] = "Y";
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "like"){
                    if(!empty($_REQUEST['DATA']['entity_type'])){
                        if($_REQUEST['DATA']['entity_type'] == "post"){
                            $entity_type = USER_POST_HLB_ID;
                        }elseif($_REQUEST['DATA']['entity_type'] == "comment"){
                            $entity_type = USER_COMMENT_HLB_ID;
                        }
                    }
                    $result = $this -> likeUnlike($_REQUEST['DATA']['entity_id'], $entity_type);
                    if(in_array($result, ['like', 'unlike']))
                        echo json_encode(array("STATUS" => "SUCCESS", "RESULT" => $result));
                    else
                        echo json_encode(array("STATUS" => "ERROR", "RESULT" => $result));
                    die();
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "loadMorePosts"){
                    $this -> arResult['POSTS'] = $this->getUserPosts($this -> arParams['USER_ID']);
                    $this -> arResult['UPLOAD_POST'] = "Y";
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "loadAllComments"){
                    if(empty($_REQUEST['post_id']))
                        die();
                    $this -> arResult['COMMENTS'] = $this->getPostComment($_REQUEST['post_id']);
                    $this -> arResult['UPLOAD_COMMENT'] = "Y";
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "addFile"){
                    if($result = $this -> addFile($_REQUEST['file_type'])){
                        echo json_encode(array("STATUS" => "SUCCESS", "DATA" => $result));
                    }else{
                        echo json_encode(array("STATUS" => "ERROR"));
                    }
                    die();
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "deleteFile"){
                    $this -> deleteFile($_REQUEST['DATA']);
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "deletePost"){
                    if($this -> deletePost($_REQUEST['post_id'])){
                        echo json_encode(array("STATUS" => "SUCCESS"));
                    }else{
                        echo json_encode(array("STATUS" => "ERROR"));
                    }
                    die();
                }elseif($this->request->get($this -> arParams['ACTION_VARIABLE']) == "deleteComment"){
                    if($this -> deleteComment($_REQUEST['comment_id'])){
                        echo json_encode(array("STATUS" => "SUCCESS"));
                    }else{
                        echo json_encode(array("STATUS" => "ERROR"));
                    }
                    die();
                }
            }else{
                if(!$this->arResult["BLACKLIST"]['CLOSED']){
                    $this->arResult['POSTS'] = $this->getUserPosts($this -> arParams['USER_ID']);
                }
            }
            if(!empty(intval($_GET['post_id'])))
                $this -> arResult['CURRENT_POST'] = $this -> getPostById($_GET['post_id']);
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
};

?>