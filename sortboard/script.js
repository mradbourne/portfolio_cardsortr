$(document).ready(function(){
	$(window).resize(function() {
		$('.card').each(function() { stretchCanvas(this); });
	}).trigger('resize');
	
	$('#picker').farbtastic('#card-color');
	showHideEdit(); //Shows or hides the features that edit the selected card.
	
	$('#sortcanvas').click(function(e){
		if (e.target.id == 'sortcanvas') {
			$('#toolbar').effect("highlight", { color:'#ffffff' }, 500);
			$('#card-body').text('');
			$('#card-color').val('#fffddd');
			$('#card-color').keyup(); //Update farbtastic picker

			$('.card.selected').removeClass('selected');
			showHideEdit();
		}
	});

	var tmp;
	
	$('.card').each(function(){
		/* Finding the biggest z-index value of the notes */
		tmp = $(this).css('z-index');
		if(tmp>zIndex) zIndex = tmp;
	});

	/* A helper function for converting a set of elements to draggables: */
	initCard($('.card'));
	
	/* The submit button: */
	$('#card-submit').click(function(e){		
		var data = {
			'zindex'	: ++zIndex,
			'body'		: $('#card-body').val(),
 			'bg_color'		: $('#card-color').val(),
 			'fg_color'		: rgb2hex($('#card-color').css('color'))
		};

		/* Sending an AJAX POST request: */
		$.post('post.php',data,function(msg){				 
			if(parseInt(msg))
			{
				/* msg contains the ID of the note, assigned by MySQL's auto increment: */
				
				
				var cardTemplate = document.createElement('div');
				var tmp = $(cardTemplate);
				tmp.attr({'class':'card', 'data-cardcreator':'Me', 'data-cardid':msg }).css({'background-color':data['bg_color'],'color':data['fg_color'],'z-index':zIndex,top:0,left:0});
				tmp.text(data['body']);
				tmp.appendTo($('#sortcanvas'));
				$(tmp).effect("highlight", { color:'#ffffff' }, 500);
				initCard(tmp);
			}
		});
		
		e.preventDefault();
	});

	
	$('#card-remove').click(function(e){
		var data = {'cid': $('.selected').attr('data-cardid')};
		$.post('cardops/remove.php',data,function(msg){				 
			if(parseInt(msg)) {
				$('.card[data-cardid="'+msg+'"]').fadeOut('500',function(){
					$(this).remove();
					showHideEdit();
					fitCanvas();
				});
			}
		});
		e.preventDefault();
	});

	$('#card-update').click(function(e){
		var data = {
			'cid': $('.selected').attr('data-cardid'),
			'body': $('#card-body').val(),
 			'bg_color': $('#card-color').val(),
 			'fg_color': rgb2hex($('#card-color').css('color'))
		};
		$.post('cardops/update.php',data,function(msg){				 
			if(parseInt(msg)) {
				$('.card[data-cardid="'+msg+'"]').fadeOut('250',function(){
					$(this).text(data['body']).css({'background-color':data['bg_color'],'color':data['fg_color']}).fadeIn('250');
				});
			}
		});
		e.preventDefault();
	});
	
	$('.card-form').live('submit',function(e){e.preventDefault();});
});

var zIndex = 0;

function initCard(elements) {
	/* Elements is a jquery object: */
	
	elements.draggable({
		containment:'parent',
		start:function(e,ui){ ui.helper.css('z-index',++zIndex); },
		stop:function(e,ui){
			$.get('updatecard.php',{
				  x		: ui.position.left,
				  y		: ui.position.top,
				  z		: zIndex,
				  id	: parseInt(ui.helper.attr('data-cardid'))
			});
			fitCanvas();
		},
		drag:function(e,ui){
			stretchCanvas(ui.helper);
		}
	});
	
	elements.click(function () {
		$('.card.selected').removeClass('selected');
		$(this).addClass('selected');
		
		//Replace toolbar content with clicked
		$(this).effect("transfer", { to: $("#toolbar") }, 150, function() {
			$('#toolbar').effect("highlight", { color:'#ffffff' }, 500);
			$('#card-body').text($(this).text());
			$('#card-color').val(rgb2hex($(this).css('background-color')));
			$('#card-color').keyup(); //Update farbtastic picker
			showHideEdit();
		});
	});
	
}

/* Other bits */
function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function stretchCanvas(el) {
	var leftAndWidth = parseFloat($(el).css('left').replace(/[^-\d\.]/g, '')) + $(el).width();
	if ( leftAndWidth > ($('#sortcanvas').width() - 100) ) {
		$('#sortcanvas').css('width', leftAndWidth + 100); //16px allows for scrollbar
	}
	var topAndHeight = parseFloat($(el).css('top').replace(/[^-\d\.]/g, '')) + $(el).height();
	if ( topAndHeight > ($('#sortcanvas').height() - 100) ) {
		$('#sortcanvas').css('height', topAndHeight + 100); //16px allows for scrollbar
	}
}
function fitCanvas() {
	var tempWidth = $('#main').width();
	var tempHeight = $('#main').height();

	$('.card').each(function() {
		var leftAndWidth = parseFloat($(this).css('left').replace(/[^-\d\.]/g, '')) + $(this).width();
		if (leftAndWidth > tempWidth - 100) {
			tempWidth = leftAndWidth + 100;
		}
		var topAndHeight = parseFloat($(this).css('top').replace(/[^-\d\.]/g, '')) + $(this).height();
		if (topAndHeight > tempHeight - 100) {
			tempHeight = topAndHeight + 100;
		}
	});
	
	$('#sortcanvas').css({'width': tempWidth, 'height': tempHeight});
}

function showHideEdit() {
	if ($('.selected').length) {
		$('#card-update').show();
	} else {
		$('#card-update').hide();
	}
}