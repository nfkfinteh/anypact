<?php
    require_once 'requestSMEV/class/vidgetForm.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отправка запроса смев</title>    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"> 

</head>
<body>
    <div class="container">
        <!--Первичный запрос в СМЭВ-->
        <div class="row">
            <div class="col-md-12">
            <!--Панель-->
            <div class="panel panel-default">
                <div class="panel-heading">Первичный запрос в СМЭВ по ИД</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <!--Ответы на запросы-->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="input-group input-group-lg" id="firstRequestText">
                                    
                                    </div>
                                </div>
                            </div>
                            <!--//Ответы на запросы-->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form id="formFirstRequestSMEV">
                                        <?php
                                            $vidgetForm = new vidgetForm();
                                            $arrFilds = array(
                                                array('ID'=>'lastname', 'TEXT'=>'Фамилия'),
                                                array('ID'=>'firstname', 'TEXT'=>'Имя'),
                                                array('ID'=>'middlename', 'TEXT'=>'Отчество'),
                                                array('ID'=>'passportSeries', 'TEXT'=>'Серия паспорта'),
                                                array('ID'=>'passportNumber', 'TEXT'=>'Номер паспорта'),
                                                array('ID'=>'snils', 'TEXT'=>'СНИЛС'),
                                                array('ID'=>'inn', 'TEXT'=>'ИНН')
                                            );
                                            $vidgetForm->renderFilds($arrFilds);
                                        ?>
                                    </form>       
                                </div>
                            </div>
                            <button id="firstRequest" class="btn btn-primary btn-lg">Отправить запрос</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--//Панель-->                
            </div>
        </div>
        <!--//Первичный запрос в СМЭВ-->
        <!--Повторный запрос в СМЭВ-->
        <div class="row">
            <div class="col-md-12">
            <!--Панель-->
            <div class="panel panel-default">
                <div class="panel-heading">Повтрный запрос в СМЭВ по ИД</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <!--Ответы на запросы-->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="input-group input-group-lg" id="RequestText">
                                    
                                    </div>
                                </div>
                            </div>
                            <!--//Ответы на запросы-->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-addon">ID запроса</span>
                                        <input type="text" class="form-control" id="idRequestMessage">
                                    </div>
                                </div>
                            </div>
                            <button id="request" class="btn btn-primary btn-lg">Отправить запрос</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--//Панель--> 
            <a href="https://nfksber.ru/esia/fb76d174eedcefd624725c79a48b210777093890/smev">Прямая ссылка на смев</a>               
            </div>
        </div>
    </div>       
</body>
<script src="script.js"></script>
</html>