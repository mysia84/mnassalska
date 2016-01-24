
(function($){
	$(document).ready(function(){
		if ($('#portfolio-formats-select').length > 0) {
			var format = $('#portfolio-formats-select input:checked').attr('value');
			$('#post-body div[id^=dh-metabox-post-]').hide();
			$('#post-body #dh-metabox-post-'+format+'').show();
			$('#portfolio-formats-select').find('input').on('click',function(){
				var format = $(this).attr('value');
				$('#post-body div[id^=dh-metabox-post-]').hide();
				$('#post-body #dh-metabox-post-'+format+'').stop(true,true).fadeIn(500);
			});
		}
		
		$('.dh-chosen-select').each(function(){
			$(this).chosen();
		});
		
	});
})(jQuery);