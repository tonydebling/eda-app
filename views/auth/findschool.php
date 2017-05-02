{% extends 'templates/default.php' %}

{% block title %}School{% endblock %}
{% block content %}

<script>
$(document).ready(function(){

	var schoolTable = [];
	var schoolSelector ="";
	var returnUrl ="{{ returnUrl }}"

	$(document).ajaxError(function(event, jqXHR, settings, thrownError){
		alert(thrownError);
	});
	$("#schlist").hide();
	
    $("#sch").keyup(function(event){
		schoolSelector = $(this).val();
		if (schoolSelector.length > 1){
			// update and display list of schools
			$("#schlist").hide();
			$("li").remove("");
			var url = "getdata/schools?sstr="+schoolSelector;
			$.getJSON(url, function(data){
				data.forEach(function(school){
					$("#schlist").append("<li id='"+school['id'] +"'>"+school['name']+"</li>")
				});
				$("li").on('click',function(){
					var id = $(this).attr('id');
					window.location.href = returnUrl+"?id="+id;
				});
			});
			$("#schlist").show();
		} else {
			// hide list of schools
			$("#schlist").hide();
			$("li").remove("");
		};    
    });
});

</script>

<style>

.scrollable-menu {
	height: auto;
	max-height:350px;
	overflow-x: hidden;
}
</style>

<div class="w3-container" style="height:500px">
	<h3>Find your school</h3>
	<input class="w3-input" style="width:50%" type="text" id="sch" placeholder= "Enter school name or postcode">
	<p></p>
	<ul class="w3-ul w3-border w3-hoverable scrollable-menu" id="schlist" style="width:50%">
	</ul>
</div>

<p></p>

{% endblock %}
