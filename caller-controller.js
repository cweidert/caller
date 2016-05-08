callerController = function () {
	var callerPage;
	
	return {
		init: function(page) {
			callerPage = page;
			
			
			$(callerPage).find('main.panel').addClass('hide');
			
			$(callerPage).on('click', '.tab', function(evt) {
				evt.preventDefault();
				var activeName = $(evt.target).data().panelName;

				var tabs = $(callerPage).find('.tab');
				$.each(tabs, function(i, tab) {
					if ($(tab).data().panelName === activeName) {
						$(tab).addClass('selected');
					} else {
						$(tab).removeClass('selected');
					}
				});

				var panels = $(callerPage).find('div.panel');
				$.each(panels, function(i, panel) {
					if ($(panel).data().panelName === activeName ) {
						$(panel).removeClass('hide');
					} else {
						$(panel).addClass('hide');
					}
				});

			});
		}
	}
}();