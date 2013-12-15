jQuery(document).ready(function($)
{
    $('.sstwitter-ui-action-twitter').entwine({
        onmatch: function ()
        {
         	$(this).click(function() {
         		var url = $(this).attr("data-url");
         		if(url) window.location = $(this).attr("data-url");
         		return false;
         	}); 
		}
    });
});
