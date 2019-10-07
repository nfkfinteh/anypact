<pre>
<? 
    //print_r($arResult['MESSAGES']);     
    $arMessage = array(
        array(
            'user' => 1,
            'data' => '2019.10.25 07:22',
            'message' => 'Участник системы Anypact подписал ваш договор <a href=\"http://anypact.nfksber.ru/my_pacts/send_contract/?ID=18\">ссылка на договор</a>'
        ),
        array(
            'user' => 9,
            'data' => '2019.10.25 07:24',
            'message' => 'Как я могу связаться с Вами?'
        ),
        array(
            'user' => 1,
            'data' => '2019.10.25 07:22',
            'message' => 'Позвоните по телефону 893738888'
        ),
        array(
            'user' => 10,
            'data' => '2019.10.25 07:22',
            'message' => 'Хорошо'
        ),
        array(
            'user' => 1,
            'data' => '2019.10.25 07:22',
            'message' => '!'
        )
    );
    //print_r($arMessage);

    //echo json_encode($arMessage);
    $arMessage  =  json_decode($arResult['MESSAGES']['UF_TEXT_MESSAGE_USER'], true);
    //print_r($arResult['MESSAGES']['UF_USERS_ID']);
    print_r($arResult['FastUserParams']);
    $UserID = CUser::GetID();

?>
</pre>
    <div>
        <h1 class="mb-4">Мои сообщения</h1>
        <div class="row pt-2 pb-5">
            <div class="col-md-4 col-sm-12">
                <h3 class="font-weight-bold">Участники</h3>
                <ul class="list-person-conversation">
                    <? foreach($arResult['UsersChart'] as $user){ ?>
                        <li class="person-conversation">
                            <div class="person-conversation-photo">                                
                                <img src="<?=$user['PERSONAL_PHOTO']?>" alt="Васильев Александр Евгеньевич">
                                <!--<img src="<?=SITE_TEMPLATE_PATH?>/image/sample_face_150x150.png" alt="Васильев Александр Евгеньевич">-->
                            </div>
                            <div class="person-conversation-name"><?=$user['LAST_NAME']?> <?=$user['NAME']?> <?=$user['SECOND_NAME']?></div>
                        </li>
                    <?}?>
                </ul>
                <button class="btn btn-nfk btn-add-person w-100">+ добавить участника</button>
            </div>
            <div class="col-md-8 col-sm-12">
                <h3 class="font-weight-bold">Обсуждение условий договора №56465465</h3>
                <div class="message-list">
                    <? foreach($arMessage as $Message){?>
                        <?if($Message['user'] == $UserID){?>
                            <div class="message-block message-block-left">
                                <div class="message-person-photo">
                                    <div class="user-avatar">
                                        <span class="user-first-letter">А</span>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/sample_face_150x150.png" alt="Васильев Александр Евгеньевич"><!--69px x 69px-->
                                    </div>
                                </div>
                                <div class="message-container">
                                    <div class="message-message">
                                        <time datetime="2019-03-01T15:12:13+03:00"><?=$Message['data']?></time>
                                        <div class="message-content">
                                            <div class="message-text">
                                                <p style="font-weight: 400;"><?=$arResult['FastUserParams'][$Message['user']]['FIO']?>:</p>
                                                <p><?=$Message['message']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}else {?>                            
                            <div class="message-block message-block-right">                        
                                <div class="message-person-photo">
                                    <div class="user-avatar">
                                        <span class="user-first-letter">И</span>
                                    </div>
                                </div>
                                <div class="message-container">
                                    <div class="message-message">
                                        <time datetime="2019-03-01T15:12:13+03:00"><?=$Message['data']?></time>
                                        <div class="message-content">
                                            <div class="message-text">
                                                <p style="font-weight: 400;"><?=$arResult['FastUserParams'][$Message['user']]['FIO']?>:</p>
                                                <p><?=$Message['message']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                    
                    <?}?>
                    <!------>
                </div>
                <div class="message-chat-input">
                    <button class="mr-1 mr-sm-3"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-image-file.png" alt=""></button>
                    <button class="mr-2 mr-sm-4"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png" alt=""></button>
                    <textarea name="" id="" placeholder="Введите сообщение"></textarea>
                    <button class="ml-1 mr-0 mx-sm-4"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-sent.png" alt=""></button>
                </div>
            </div>
        </div>
    </div>
</div>