/* ************************  Rechercher serie ************************************ */
$(document).ready(function() { // rechercher barre du haut
		$('#form_search_serie').on('submit', function(e) { // Si le formulaire est submit
		e.preventDefault(); 
		$('.loading').css("visibility", "visible");
		$(".alert").fadeToggle(500);
		$("#results").slideUp(500);
        var serie_name = $('#serie_name').val();
		$.ajax({
			url: "http://localhost/Symfony2/web/app_dev.php/get_serie",
			type: "POST",
			data : 'serie_name=' + serie_name,
			success: function(data) { 
				$('.loading').css("visibility", "hidden");
				var nb_serie = data.nb_serie;
				var content = data.content;
				if(nb_serie == '0' || nb_serie > 12) {							// 0 série trouvée
					$("#results").html(getAlertError('Echec', content));
					$("#results").show();
					$(".alert").fadeToggle(500);	
				}
				// else if(code == '1') {						// 1 série trouvée
					// url = "http://localhost/Symfony2/web/app_dev.php/serie/"+action;
					// $(location).attr("href", url);
				// }
				else {   									// Plusieurs séries trouvées
					$("#results").html(content);
					$("#results").slideDown(500);
				}				
			}
		});
  });
});
function closeSearchSerie(id)
{
	$("#results").slideToggle(500);
}

/* ************************  Importer série (obsolète) ************************************ */
$(document).ready(function() {
    
	$('#bloc_serie').fadeIn(1000);
	// Lorsque je soumets le formulaire
    $('#form_add_series').on('submit', function(e) {
        e.preventDefault(); // J'empeche le comportement par deut du navigateur, c-à-d de soumettre le formulaire
		$(".result_query").slideUp(500);
		$('#bloc_test').html(getAlertSuccess('YEAH !!!', 'la nouvelle série a bien été ajouté !'));	
		$('#bloc_test').fadeIn(500);
        var $this = $(this); 
        var serie_name = $('.serie_name_value').val();
		$('.loading').css("visibility", "visible");
		$.ajax({
			url: $this.attr('action'), 
			type: $this.attr('method'),
			data : 'serie_name=' + serie_name,
			success: function(html) { 
				$(".result_query").html(html);
				$(".result_query").slideDown(500);
				$('.loading').css("visibility", "hidden");
			}
		});
	})
});

/* ************************  Alert ************************************ */
function getLabelInfo(content) {
	return "<span class='label label-info'>"+content+"</span> ";
}
function getAlertSuccess(title, content) {
	return "<div class='alert alert_success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p class='title_alert'>"+title+"</p>"+content+"</div>";
}
function getAlertError(title, content) {
	return "<div class='alert alert_error'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p class='title_alert'>"+title+"</p>"+content+"</div>";
}