function hoverButton() 
{
	$( "#button_collected" )
		.mouseover(function() {
			$( this ).removeClass("btn-primary").addClass("btn-danger");
			$( this ).html("X");
	  })
		.mouseout(function() {
			$( this ).removeClass("btn-danger").addClass("btn-primary");
			$( this ).html("Dans ma collection");
	});
}

/* ************************  Action on delete ************************************ */
function delete_action_serieInfo(serie_id) {
	$( "#div_button" ).html('<a href="javascript:getModalForm('+serie_id+')"><button type="button" class="btn btn-success btn-lg">Ajouter à ma collection</button></a>');
}

/* ************************  Action on add ************************************ */
function add_action_serieInfo(serie_id) {
	$( "#div_button" ).html('<button type="button" id="button_colleced" class="btn btn-primary btn-lg">Dans ma collection</button> <a href="javascript:deleteCollected('+serie_id+')"><button type="button" id="button_collected" class="btn btn-danger btn-lg">X</button></a>');
}