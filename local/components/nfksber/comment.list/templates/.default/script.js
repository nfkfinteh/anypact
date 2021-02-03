$(function() {
	//обновление листа
	function updateList(){
		let url = window.location.href;
		let data = {
			"ACTION_NAV":'nav'
		};
		//обновление списка комментариев
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			async:false,
			success: function(result){
				$('#comment_container_list').html(result);
			}
		});
	}


	$("body").on("click", "#comment_container .cardPact-comment-submit", function() {
		let url = '/response/ajax/add_new_messag_sdelka.php';
		let text = $('#comment_container textarea').val();
		let body = $('#comment_container');

		let data = {
			"iblock_id" : bitrixJS.IBLOCK_ID,
			"object" : bitrixJS.ID_SDELKA,
			"message-text" : text,
			"user_id" : bitrixJS.USER_ID,
			"sessid": BX.bitrix_sessid(),
			"active" : 'Y'
		};

		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			async:false,
			success: function(result){
				$result = JSON.parse(result);
				if($result['TYPE']=='ERROR'){
					console.log($result['VALUE']);
				}
				if($result['TYPE']=='SUCCESS'){
					console.log($result);
					$('#comment_container textarea').val('');
					$(body).html(
						"<div>Коментарий отобразится после модерации</div>"
					);

					/*form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();*/
				}
			}
		});

		updateList();
	});

	//получения поля для редактирования
	$(document).on('click', '.cardPact-comment-edit', function(){
		let url = window.location.href;
		let text = $(this).parents('.comment-block').eq(0).find('.cardPact-comment-body').text().trim();
		let id_comment = $(this).attr('data-comment');
		//let textarea = $('#comment_container').find('textarea');

		let data = {
			"ACTION_NAV":'nav',
			"COMMENT_EDIT":'Y',
			"EDIT_ID":id_comment
		};
		//обновление списка комментариев
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(result){
				$('#comment_container_list').html(result);
			}
		});

	});

	//сохранение измененйи
	$(document).on('click', '#edit_comment_container .cardPact-comment-submit', function(){
		let url = '/response/ajax/edit_comment.php';
		let text = $('#edit_comment_container textarea').val().trim();
		let id_comment = $(this).attr('data-coment_id');

		let data = {
			'id':id_comment,
			'text':text,
			'action':'edit'
		};

		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(result){
				updateList();
			}
		});

	});

	//удаление комментария
	$(document).on('click', '.cardPact-comment-delete', function(){
		let url = '/response/ajax/edit_comment.php';
		let id_comment = $(this).attr('data-comment');

		let data = {
			'id':id_comment,
			'action':'delete'
		};

		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(result){
				updateList();
			}
		});

	});


});
