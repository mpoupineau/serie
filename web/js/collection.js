function sortCollection() 
{
	$('#sortByStatus').click(function(){
		sortBy('status');
	});
	$('#sortByRated').click(function(){
		sortBy('rated');
	});
	$('#sortByFirstAired').click(function(){
		sortBy('firstAired');
	});
	$('#sortByNbSeasons').click(function(){
		sortBy('nbSeasons');
	});
}

function sortBy(type)
{
	changeActiveLink(type);
	$("#collection_tab").fadeOut(500);
	$.ajax({
            url : Routing.generate('app_collection_sort'),
            type : "POST",
			data : 'type_sort=' + type,
			success : function(data){   
				$("#collection_tab").html(data);
				$("#collection_tab").fadeIn(500);
			},
			error : function(resultat, statut, erreur){
				$("#results").html(getAlertError('Echec', 'Une erreur est survenue'));
				$("#results").show();
				$(".alert").fadeToggle(500);
					 
			}
	});	
}

function changeActiveLink(type)
{
	$('#sortByStatus').parent().removeClass("active");
	$('#sortByRated').parent().removeClass("active");
	$('#sortByFirstAired').parent().removeClass("active");
	$('#sortByNbSeasons').parent().removeClass("active");
	
	if(type == "status")
		$('#sortByStatus').parent().addClass("active");
	if(type == "rated")
		$('#sortByRated').parent().addClass("active");
	if(type == "firstAired")
		$('#sortByFirstAired').parent().addClass("active");
	if(type == "nbSeasons")
		$('#sortByNbSeasons').parent().addClass("active");
}