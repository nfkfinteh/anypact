$(function() {
	$("body").on("click", "#comment_container .cardPact-comment-submit", function() {
		let url = '/response/ajax/add_new_messag_sdelka.php';
		let text = $('#comment_container textarea').val();
		let body = $('#comment_container');

		let data = {
			"iblock_id" : bitrixJS.IBLOCK_ID,
			"object" : bitrixJS.ID_SDELKA,
			"message-text" : text,
			"login" : bitrixJS.CURENT_USER.LOGIN,
			"active" : 'N'
		}
		console.log(data);
		$.post(url, data, function(result) {
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
		});
	});

	$( '#my-slider' ).sliderPro({
		width : "100%",
		aspectRatio : 1.6, //соотношение сторон
		loop : false,
		autoplay : false,
		fade : true,
		thumbnailWidth : 164,
		thumbnailHeight : 101,
		breakpoints: {
			450: {
				thumbnailWidth : 82,
				thumbnailHeight : 50
			}
		}
	});
});
