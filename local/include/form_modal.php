<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<style>
        .toast{
            position: fixed;
            top: 0;
            right: 0;
            width: 285px;
        }
        .toast .toast-card{
            position: relative;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 4px;
            background-color: #ffffff;
            padding: 13px 15px 13px 20px;
            margin: 20px 25px;
            font-size: 15px;
        }
        .toast .toast-close{
            position: absolute;
            right: 3px;
            top: 0;
            border: none;
            padding: 0 5px;
            background: none;
            cursor: pointer;
            opacity: .6;
            transition: all .2s ease;
        }
        .toast .toast-close:focus{
            outline: none;
        }
        .toast .toast-card:hover .toast-close{
            opacity: 1;
        }
        .toast .toast-body{
            display: flex;
        }
        .toast .toast-card.toast-status .toast-body,
        .toast .toast-card.toast-message .toast-body{
            margin-left: 12px;
        }
        .toast .toast-card.toast-status .toast-body span{
            color: #a6a6a6;
        }
        .toast img{
            width: 46px;
            height: 46px;
            min-height: 46px;
            min-width: 46px;
            border-radius: 50%;
            object-fit: cover;
            position: absolute;
            left: -23px;
        }
        @media screen and (max-width: 543px){
            .toast{
                width: 100%;
            }
            .toast .toast-card {
                margin: 15px;
            }
            .toast .toast-card .toast-body{
                font-size: 13px;
            }
            .toast .toast-card.toast-status .toast-body,
            .toast .toast-card.toast-message .toast-body {
                margin-left: 0;
            }
            .toast img{
                position: relative;
                left: 0;
                margin-right: 13px;
            }
        }
    </style>
    <div class="toast" id="popup-success">
        <div class="toast-card">
            <button class="toast-close">×</button>
            <div class="toast-body">
                <div>
                    <a href="#" class="popup__title title">Данные сохранены</a>
                </div>
            </div>
        </div>
        <div class="toast-card toast-status">
            <button class="toast-close">×</button>
            <div class="toast-body">
                <img src="image/sample_face_150x150.png" alt="">
                <div>
                    <a href="#">Екатерина Андреева</a>
                    <span>Участник системы Anypact подписал ваш договор</span>
                </div>
            </div>
        </div>
        <div class="toast-card toast-message">
            <button class="toast-close">×</button>
            <div class="toast-body">
                <img src="image/sample_face_150x150.png" alt="">
                <div>
                    <a href="#">Екатерина Андреева</a>
                    <span>Участник системы Anypact подписал ваш договор</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid"></div>
<!--    
<div class="popup-wrapper" id="popup-success">
    <div class="popup-wrapper__flex">
        <div class="popup">
            <div class="popup__content">
                <div class="popup__success">
                    <img src="/local/templates/anypact/img/success.svg" alt="" role="presentation"/>
                </div>
                <h3 class="popup__title title">
                </h3>
                <p class="popup__subtitle">
                </p>
            </div>
        </div>
    </div>
</div>
<div class="popup-wrapper" id="popup-error">
    <div class="popup-wrapper__flex">
        <div class="popup">
            <div class="popup__content">
                <div class="popup__success">
                    <img src="/local/templates/anypact/img/error.svg" alt="" role="presentation"/>
                </div>
                <h3 class="popup__title title">
                </h3>
                <p class="popup__subtitle">
                </p>
            </div>
        </div>
    </div>
</div>
-->