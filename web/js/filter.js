
$(function() {
	/* ************************  Menu filtre ************************************ */
	var now = new Date()
	$( "#slider-range-years" ).slider({
		range: true,
		min: 1950,
		max: now.getFullYear(),
		values: [ 1950, now.getFullYear() ],
		slide: function( event, ui ) {
			$( "#years_range" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
		}
	});
	$( "#years_range" ).val( "" + $( "#slider-range-years" ).slider( "values", 0 ) +
	  " - " + $( "#slider-range-years" ).slider( "values", 1 ) );

	$( "#slider-range-rating" ).slider({
		range: true,
		min: 0,
		max: 10,
		values: [ 0, 10 ],
		slide: function( event, ui ) {
			$( "#rating_range" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
		}
	});
	$( "#rating_range" ).val( "" + $( "#slider-range-rating" ).slider( "values", 0 ) +
	  " - " + $( "#slider-range-rating" ).slider( "values", 1 ) );
	

	/* ************************  Submit filter ************************************ */
	$('.filter_submit').click(function(){
		submitFilter(0);
	});

});
function submitFilter(num_page)
{
	$("#results").fadeOut(500);
	$('.loading2').show();
	var years_range	 = initYearsRange();
	var tab_category = initCategory();
	var rating_range   = initRating();
	var continuing   = initContinuing();
	$("#bloc_serie").fadeOut(500);
	$.ajax({
		url: "http://localhost/Symfony2/web/app_dev.php/get_series_filter",
		type: "POST",
		data : 'years_range=' + years_range +'&tab_category=' + tab_category +'&rating_range=' + rating_range +'&continuing=' + continuing + '&num_page=' + num_page,
		dataType : 'json',
		success: function(data) { 
			$("#bloc_serie").html(data.html);
			$("#bloc_serie").fadeIn(500);
			$('.loading2').hide();
            if(!data.changePage) {
                $("#results").html(getAlertSuccess('Bien joué', data.nb_series+' séries correspondent à vos critères'));
                $("#results").show();
                $(".alert").fadeToggle(0);
            }
		},
		error : function(resultat, statut, erreur){
			$('.loading2').hide();
			$("#results").html(getAlertError('Echec', 'Une erreur est survenu'+num_page));
			$("#results").show();
			$(".alert").fadeToggle(500);
				 
		}
	});
	
}
function initYearsRange()
{
	var years_range  = $('#years_range').val();
	$("#label_years").hide();
	
	if(years_range != "1950 - 2016")
		$("#label_years").html(getLabelInfo(years_range));
	else 
		$("#label_years").html("");
	
	$("#label_years").fadeIn(500);
	
	return years_range;
}
function initCategory()
{
	var tab_category = [];
	if($('#check_action').is(':checked'))
		tab_category.push($('#check_action').val());
	if($('#check_animation').is(':checked'))
		tab_category.push($('#check_animation').val());
	if($('#check_adventure').is(':checked'))
		tab_category.push($('#check_adventure').val());	
	if($('#check_comedy').is(':checked'))
		tab_category.push($('#check_comedy').val());
	if($('#check_crime').is(':checked'))
		tab_category.push($('#check_crime').val());	
	if($('#check_drama').is(':checked'))
		tab_category.push($('#check_drama').val());
	if($('#check_children').is(':checked'))
		tab_category.push($('#check_children').val());	
	if($('#check_family').is(':checked'))
		tab_category.push($('#check_family').val());
	if($('#check_fantasy').is(':checked'))
		tab_category.push($('#check_fantasy').val());
	if($('#check_horror').is(':checked'))
		tab_category.push($('#check_horror').val());
	if($('#check_mini_serie').is(':checked'))
		tab_category.push($('#check_mini_serie').val());
	if($('#check_mystery').is(':checked'))
		tab_category.push($('#check_mystery').val());
	if($('#check_reality').is(':checked'))
		tab_category.push($('#check_reality').val());
	if($('#check_romance').is(':checked'))
		tab_category.push($('#check_romance').val());
	if($('#check_science_fiction').is(':checked'))
		tab_category.push($('#check_science_fiction').val());
	if($('#check_suspense').is(':checked'))
		tab_category.push($('#check_suspense').val());
	if($('#check_thriller').is(':checked'))
		tab_category.push($('#check_thriller').val());
	if($('#check_western').is(':checked'))
		tab_category.push($('#check_western').val());
	
	$("#label_category").hide();
	
	$("#label_category").html("");
    if(tab_category.length < 18) {
        for (i = 0; i < tab_category.length; ++i)
        {
            $("#label_category").append(getLabelInfo(tab_category[i]));
        }
    }
	$("#label_category").fadeIn(500);
	
	return tab_category;
}
function initRating()
{
	var rating_range = $('#rating_range').val();
	$("#label_rating").hide();

	if(rating_range != "0 - 10")
		$("#label_rating").html(getLabelInfo(rating_range));
	else 
		$("#label_rating").html("");
	
	$("#label_rating").fadeIn(500);
	
	return rating_range;
}
function initContinuing()
{
	var continuing = "none";
	if($('input[name=continuing]').is(':checked'))
		continuing = $('input[name=continuing]:checked').val();
	
	$("#label_continuing").hide();	
	$("#label_continuing").html("");
	if(continuing != "none")
		$("#label_continuing").html(getLabelInfo(continuing));
	
	$("#label_continuing").fadeIn(500);
	
	return continuing;
}
function checkAll()
{
	$("#check_action").prop( "checked", true );
	$("#check_animation").prop( "checked", true );
	$("#check_adventure").prop( "checked", true );
	$("#check_comedy").prop( "checked", true );
	$("#check_crime").prop( "checked", true );
	$("#check_drama").prop( "checked", true );
	$("#check_children").prop( "checked", true );
	$("#check_family").prop( "checked", true );
	$("#check_fantasy").prop( "checked", true );
	$("#check_horror").prop( "checked", true );
	$("#check_mini_serie").prop( "checked", true );
	$("#check_mystery").prop( "checked", true );
	$("#check_reality").prop( "checked", true );
	$("#check_romance").prop( "checked", true );
	$("#check_science_fiction").prop( "checked", true );
	$("#check_suspense").prop( "checked", true );
	$("#check_thriller").prop( "checked", true );
	$("#check_western").prop( "checked", true );
}
function uncheckAll()
{
	$("#check_action").prop( "checked", false );
	$("#check_animation").prop( "checked", false );
	$("#check_adventure").prop( "checked", false );
	$("#check_comedy").prop( "checked", false );
	$("#check_crime").prop( "checked", false );
	$("#check_drama").prop( "checked", false );
	$("#check_children").prop( "checked", false );
	$("#check_family").prop( "checked", false );
	$("#check_fantasy").prop( "checked", false );
	$("#check_horror").prop( "checked", false );
	$("#check_mini_serie").prop( "checked", false );
	$("#check_mystery").prop( "checked", false );
	$("#check_reality").prop( "checked", false );
	$("#check_romance").prop( "checked", false );
	$("#check_science_fiction").prop( "checked", false );
	$("#check_suspense").prop( "checked", false );
	$("#check_thriller").prop( "checked", false );
	$("#check_western").prop( "checked", false );
}
function uncheckContinuing()
{
	$('input[name=continuing]').prop("checked", false );
}