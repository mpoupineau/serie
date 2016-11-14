/* ************************  Créer form ajout série ************************************ */
function getModalForm(serie_id, user_id)
{
	$("#results").hide(500);
	$('.loading').show();
	$.ajax({
       url : "http://localhost/Symfony2/web/app_dev.php/form_new_collected",
      // url : "http://localhost/Symfony2/web/app_dev.php"+Routing.generate('serie_data_formnewcollected'),
	   type : "POST",
	   data : 'serie_id=' + serie_id+'&user_id=' + user_id,
       success : function(data){ // code_html contient le HTML renvoyé           
			$("#add_serie").html(data);
			$('.bs-example-modal-lg').modal('show');
       },
		error : function(resultat, statut, erreur){
			$('.loading').hide();
			$("#results").html(getAlertError('Echec', 'Une erreur est survenu'));
			$("#results").show();
			$(".alert").fadeToggle(500);
				 
		}
    });
}
/* ************************  Suppression serie d'une collection ************************************ */
function submitDelete(imagePath) 
{
	$('#submitDelete').click(function(){
		$('.bs-example-modal-lg').modal('hide');
		var collected_id  = $('#delete_collectedId').val();
		var serie_id  = $('#delete_serieId').val();
		var user_id  = $('#delete_userId').val();
		var confirmation = true;
		$("#results").hide(500);
		$.ajax({
			url : "http://localhost/Symfony2/web/app_dev.php/delete_collected",
			// url : "http://localhost/Symfony2/web/app_dev.php"+Routing.generate('serie_data_formnewcollected'),
			type : "POST",
			data : 'collected_id=' + collected_id +'&confirmation=' + confirmation,
			success : function(data){           
				$("#results").html(getAlertSuccess(data.title, data.content));
				$("#results").show();
				$(".alert").fadeToggle(500);
				// $("#serie_"+serie_id).remove();
				var url_path = window.location.pathname;
				if (url_path.indexOf("/collection") >= 0)
					$("#serie_"+serie_id).remove();
				else if (url_path.indexOf("/serie/") >= 0) {
					delete_action_serieInfo(serie_id);
					updateColor(serie_id, data.css_status, 'none', user_id, imagePath);
				}
				else
					updateColor(serie_id, data.css_status, 'none', user_id, imagePath);
			},
			error : function(resultat, statut, erreur){
				$("#results").html(getAlertError('Echec', 'Une erreur est survenue'));
				$("#results").show();
				$(".alert").fadeToggle(500);
					 
			}
		});	
	});
}
/* ************************  Confirmation d'une suppresion ************************************ */
function deleteCollected(serie_id, user_id)
{
	var confirmation = false;
	$("#results").hide(500);
	$.ajax({
       url : "http://localhost/Symfony2/web/app_dev.php/delete_collected",
      // url : "http://localhost/Symfony2/web/app_dev.php"+Routing.generate('serie_data_formnewcollected'),
	   type : "POST",
	   data : 'serie_id=' + serie_id +'&user_id=' + user_id+'&confirmation=' + confirmation,
       success : function(data){ // code_html contient le HTML renvoyé           
			$("#add_serie").html(data);
			$('.bs-example-modal-lg').modal('show');
       },
		error : function(resultat, statut, erreur){
			$("#results").html(getAlertError('Echec', 'Une erreur est survenue'));
			$("#results").show();
			$(".alert").fadeToggle(500);
				 
		}
    });
}
/* ************************  Ajout d'une serie dans une collection ************************************ */
function submitAdd(imagePath) 
{
	$('#submitAdd').click(function(){
		var rating  = $('#collected_rated').val();
		var seasonSeen  = $('#collected_seasonSeen').val();
		var episodeSeen  = $('#collected_episodeSeen').val();
		var comment  = $('#collected_comment').val();
		var serie_id  = $('#serie_id').val();
		var user_id  = $('#user_id').val();
		var follow = false;
		if($('#collected_follow').is(':checked'))
				follow = true;
		var alertEachEpisode = false;
		if ($('#collected_alertEachEpisode').length) 
			if($('#collected_alertEachEpisode').is(':checked'))
				alertEachEpisode = true;
		var alertFirstEpisode = false;
		if ($('#collected_alertFirstEpisode').length) 
			if($('#collected_alertFirstEpisode').is(':checked'))
				alertFirstEpisode = true;
		var alertLastEpisode = false;
		if ($('#collected_alertLastEpisode').length) 
			if($('#collected_alertLastEpisode').is(':checked'))
				alertLastEpisode = true;
		
		$.ajax({
			url : "http://localhost/Symfony2/web/app_dev.php/add_collected",
			type : "POST",
			data : 'serie_id='+serie_id+'&user_id='+user_id+'&comment='+comment+'&seasonSeen='+seasonSeen+'&episodeSeen='+episodeSeen+'&rating='+rating+'&follow='+follow+'&alertEachEpisode='+alertEachEpisode+'&alertFirstEpisode='+alertFirstEpisode+'&alertLastEpisode='+alertLastEpisode ,
			dataType: 'json',
			success : function(data){ // code_html contient le HTML renvoyé    
				$("#results").html(getAlertSuccess(data.title, data.content));
				$("#results").show();
				$(".alert").fadeToggle(500);
				var url_path = window.location.pathname;
				if (url_path.indexOf("/serie/") >= 0) {
					add_action_serieInfo(serie_id, user_id);
				}
				updateColor(serie_id, 'none', data.css_status, user_id, imagePath); 				
			},
			error : function(resultat, statut, erreur){
				$('.loading').hide();
				$("#results").html(getAlertError('Echec', 'Une erreur est survenu'));
				$("#results").show();
				$(".alert").fadeToggle(500);			 
			}
		});
		$('.bs-example-modal-lg').modal('hide');		
	});
}
/* ************************  Modification d'une serie dans une collection ************************************ */
function submitUpdate(imagePath) 
{
	$('#submitUpdate').click(function(){
		var rating  = $('#collected_rated').val();
		var seasonSeen  = $('#collected_seasonSeen').val();
		var episodeSeen  = $('#collected_episodeSeen').val();
		var comment  = $('#collected_comment').val();
		var serie_id  = $('#serie_id').val();
		var user_id  = $('#user_id').val();
		var follow = false;
		if($('#collected_follow').is(':checked'))
				follow = true;
		var alertEachEpisode = false;
		if ($('#collected_alertEachEpisode').length) 
			if($('#collected_alertEachEpisode').is(':checked'))
				alertEachEpisode = true;
		var alertFirstEpisode = false;
		if ($('#collected_alertFirstEpisode').length) 
			if($('#collected_alertFirstEpisode').is(':checked'))
				alertFirstEpisode = true;
		var alertLastEpisode = false;
		if ($('#collected_alertLastEpisode').length) 
			if($('#collected_alertLastEpisode').is(':checked'))
				alertLastEpisode = true;
		
		$.ajax({
			url : "http://localhost/Symfony2/web/app_dev.php/add_collected",
			type : "POST",
			data : 'serie_id='+serie_id+'&user_id='+user_id+'&comment='+comment+'&seasonSeen='+seasonSeen+'&episodeSeen='+episodeSeen+'&rating='+rating+'&follow='+follow+'&alertEachEpisode='+alertEachEpisode+'&alertFirstEpisode='+alertFirstEpisode+'&alertLastEpisode='+alertLastEpisode ,
			dataType: 'json',
			success : function(data){ // code_html contient le HTML renvoyé    
				$("#results").html(getAlertSuccess(data.title, data.content));
				$("#results").show();
				$(".alert").fadeToggle(500);
				updateColor(serie_id, data.css_status_before, data.css_status_after, user_id, imagePath, 'add') 
			},
			error : function(resultat, statut, erreur){
				$('.loading').hide();
				$("#results").html(getAlertError('Echec', 'Une erreur est survenu'));
				$("#results").show();
				$(".alert").fadeToggle(500);			 
			}
		});
		$('.bs-example-modal-lg').modal('hide');		
	});
}
/* ************************  Annulation d'une action ************************************ */
function cancelAction() 
{
	$('#cancelAction').click(function(){
		$('.bs-example-modal-lg').modal('hide');
	});
}
/* ************************ Modification de la couleur d'une série ************************************ */
function updateColor(serie_id, css_status_before, css_status_after, user_id, imagePath) 
{
	if(css_status_before == 'none') {
		if(css_status_after == "Not started") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect").addClass("shadow_effect_not_started");
		else if(css_status_after == "Continuing") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect").addClass("shadow_effect_continuing");
		else if(css_status_after == "Ended") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect").addClass("shadow_effect_ended");
		$("#serie_"+serie_id).find(".addDeleteSerie").html(
		"<a href='javascript:getModalForm("+serie_id+","+user_id+")'><img src='"+imagePath+"update.png') }}' alt='MAJ' /></a>"+
		"<a href='javascript:deleteCollected("+serie_id+","+user_id+")'><img src='"+imagePath+"delete2.png') }}' alt='sup' /></a>");
	}
	else if(css_status_after == 'none') {
		if(css_status_before == "Not started") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_not_started").addClass("shadow_effect");
		else if(css_status_before == "Continuing") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_continuing").addClass("shadow_effect");
		else if(css_status_before == "Ended") 
			$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_ended").addClass("shadow_effect");
		$("#serie_"+serie_id).find(".addDeleteSerie").html(
		"<a href='javascript:getModalForm("+serie_id+",0)'><img src='"+imagePath+"add.png') }}' alt='add' /></a>");
	}
	else {
		if(css_status_before == "Not started") {
			if(css_status_after == "Continuing")  
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_not_started").addClass("shadow_effect_continuing");
			else if(css_status_after == "Ended") 
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_not_started").addClass("shadow_effect_ended");
		}
		else if(css_status_before == "Continuing")  {
			if(css_status_after == "Not started")  
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_continuing").addClass("shadow_effect_not_started");			
			else if(css_status_after == "Ended") 
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_continuing").addClass("shadow_effect_ended");
		}			
		else if(css_status_before == "Ended") {
			if(css_status_after == "Not started")  
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_ended").addClass("shadow_effect_not_started");			
			else if(css_status_after == "Continuing") 
				$("#serie_"+serie_id).find(".hovereffect").removeClass("shadow_effect_ended").addClass("shadow_effect_continuing");
		}
			
	}
	
}
/* ************************  Slider nombre de saison vue ************************************ */
function slider_seasonSeen(nbSeasonSeen, nbSeasons, seasonStatus, nbEpisodesBySeason) 
{
	 $( ".sliderSeasonSeen" ).slider({
		animate: true,
			range: "min",
			value: nbSeasonSeen,
			min: 0,
			max: nbSeasons,
			step: 1,
			
			//this gets a live reading of the value and prints it on the page
			slide: function( event, ui ) {
				$("#collected_seasonSeen").attr({value:ui.value});
				$( "#season_result" ).css("color", "#0099FF");
				if(ui.value  === 0)
					$( "#season_result" ).html("Aucune saison vue");
				/*else if(ui.value  === nbSeasons && seasonStatus == "Ended" ) {
					$( "#season_result" ).html("Série terminée");
					$( "#season_result" ).css("color", "red");
				}*/
				else
					$( "#season_result" ).html("Saison "+ ui.value );
				
				// Update episode number
				$( ".sliderEpisodeSeen" ).slider({
						value: 0,
						max: nbEpisodesBySeason[ui.value]
				});
				$("#collected_episodeSeen").attr({value:0});
				$( "#episode_result" ).html("");
			}
	 }); 
}
/* ************************  Slider nombre d'episode vue ************************************ */
function slider_episodeSeen(nbEpisodeSeen, nbEpisodesBySeason, nbSeasons, seasonStatus) 
{
	 $( ".sliderEpisodeSeen" ).slider({
		animate: true,
			range: "min",
			value: nbEpisodeSeen,
			min: 0,
			max: nbEpisodesBySeason[$("#collected_seasonSeen").attr('value')],
			step: 1,
			
			//this gets a live reading of the value and prints it on the page
			slide: function( event, ui ) {
				$("#collected_episodeSeen").attr({value:ui.value});
				$( "#season_result" ).html("Saison "+ $("#collected_seasonSeen").attr('value') );
				$( "#episode_result" ).css("color", "#0099FF");
				if(ui.value  === 0)
					$( "#episode_result" ).html("");
				else if(ui.value  === nbEpisodesBySeason[$("#collected_seasonSeen").attr('value')] && $("#collected_seasonSeen").attr('value') == nbSeasons && seasonStatus == "Ended" ) {
					$( "#season_result" ).html("");
					$( "#episode_result" ).html("Série terminée");
					$( "#episode_result" ).css("color", "red");
				}
				else 
					$( "#episode_result" ).html("Episode "+ ui.value);
			}
	 }); 
}
/* ************************   ************************************ */
function idNum(id)
  {
    return id.substring(id.indexOf('_')+1);
  }
/* ************************  Checkbox alerte par mail ************************************ */
function checkAlert() {
	  $('#collected_alertEachEpisode').click(function(){
	  if( $('#collected_alertEachEpisode').is(':checked') ) {
		$("#collected_alertFirstEpisode").prop( "checked", true );
		$("#collected_alertLastEpisode").prop( "checked", true );
		$("#collected_alertFirstEpisode").prop( "disabled", true );
		$("#collected_alertLastEpisode").prop( "disabled", true );
	  }
	  else {
		$("#collected_alertFirstEpisode").prop( "disabled", false );
		$("#collected_alertLastEpisode").prop( "disabled", false );
	  }
  });
}

/* ************************  Fonction de notation ************************************ */
function rating(imagePath) {
	var srcInLeft="<img src='"+imagePath+"star_left.png' class='star'/>"; 
	var srcInRight="<img src='"+imagePath+"star_right.png' class='star'/>"; 
	var srcOutLeft="<img src='"+imagePath+"star_left_empty.png' class='star'/>"; 
	var srcOutRight="<img src='"+imagePath+"star_right_empty.png' class='star'/>"; 


  $('.astar').click(function(){
    var id=idNum($(this).attr('id')); 
    var nbStars=$('.astar').length; 
    var i;
    for (i=1;i<=nbStars;i++)
    {
		if(i<=id) 
		{
			if((i%2) == 0)
				$('#star_'+i).html(srcInRight);
			else 
				$('#star_'+i).html(srcInLeft);
		}
		else if(i>id) 
		{
			if((i%2) == 0)
				$('#star_'+i).html(srcOutRight);
			else 
				$('#star_'+i).html(srcOutLeft);
		}
		if(i==id)$('#collected_rated').attr({value:i}); // affectation de la note au formulaire
    }
  });
}

