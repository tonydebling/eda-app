{% extends 'templates/defaultjQ.php' %}

{% block title %}Checklist{% endblock %}

{% block content %}
	<script>
	$(document).ready(function(){

		var ratingsContent = '{{ ratings }}';
		var ratings = [];
		for (i = 0; i < ratingsContent.length; i++){
			ratings.push(Number(ratingsContent[i]));
		}
		ratings[1]=4;
		ratings[9]=4;
		ratings[12]=4;
		ratings[13]=4;	
		
		var jsonLookUpTable = '{{ jsonLookUpTable|raw}}';
		var lookUp = JSON.parse(jsonLookUpTable);

		function isHot(line){
			return (Number(ratings[line]) > 3);
		};
		
		function setHot(line){
			if (!isHot(line)) {ratings[line] +=4;}	
		};
		
		function clearHot(line){
			if (isHot(line)) {ratings[line] -=4;}
			};		

		$("i.fa-fire").each(function(index) {
			var line = $(this).attr('line');
			if (isHot(line)) {
				$(this).css("color","red");
			};
			$(this).attr("up", lookUp[line]['parent']);
			
		});

		$("[togg]").on('click',function(){
			var id = $(this).attr('line');
			$('#desc-s'+id).toggle();
			$('#b'+id).toggleClass("fa-toggle-down fa-toggle-up");
		});

		$("[rag]").on({
			click: function(){
				var id = $(this).attr('line');
				ragvalue = ratings[id];
				if (isHot(id)) {
					ragvalue -=4;
				};
				if (ragvalue == 0) {
	//				$(this).toggleClass("fa-stop fa-thumbs-down");
					$(this).css("color","red");
					ratings[id] +=1;
				} else if (ragvalue == 1){
	//				$(this).toggleClass("fa-thumbs-down fa-pause");
					$(this).css("color","orange");
					ratings[id] +=1;
				} else if (ragvalue == 2){
	//				$(this).toggleClass("fa-pause fa-thumbs-up");
					$(this).css("color","green");
					ratings[id] +=1;
				} else {
	//				$(this).toggleClass("fa-thumbs-up fa-thumbs-down");
					$(this).css("color","red");
					ratings[id] -=2;
				};
			},
			dblclick: function() {
				var id = $(this).attr('line');
				if (isHot(id)) {
					ratings[id] = 7;
				} else {
					ratings[id] = 3;
				};
				$(this).css("color","green");
			}
		});
		
		$("i.fa-fire").on('click',function(){
			var line = $(this).attr('line');
			if (lookUp[line]['type'] != 'c'){
				return;
			};
			if (isHot(line)) {
				$(this).css("color","lightgray");
				clearHot(line);
				var topic = lookUp[line]['parent'];
				var unit = lookUp[topic]['parent'];
				var allClear = true;
				i = topic + 1;
				while (lookUp[i]['type'] != 't') {
					if (isHot(i)) {
						allClear = false;
					}
					i += 1;
				};
				if (allClear) {
					clearHot(topic);
					$("#hot"+topic).css("color","lightgray");
					allClear = true;
					i = unit + 1;
					while (lookUp[i]['type'] != 'u') {
						if (isHot(i)) {
							allClear = false;
						}
						i += 1;
					};
					if (allClear){
						clearHot(unit);
						$("#hot"+unit).css("color","lightgray");
						}
				}
			} else {
				$(this).css("color","red");
				setHot(line);
				var topic = lookUp[line]['parent'];
				$("#hot"+topic).css("color","red");
				setHot(topic);
				var unit = lookUp[topic]['parent'];
				setHot(unit);
				$("#hot"+unit).css("color","red");
			};		
		});
	
	});
	
	</script>

	<div class="w3-responsive">
	{% for line in checklist %}
		{% if line.type == 'r' %}
			<h3> {{line.text}} </h3>
			<div><div>
		{% elseif line.type == 'u'%}
			</div></div>
			<div>
			<i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0></i>
			<i class="fa fa-toggle-up" id="b{{line.id}}" togg=true line={{line.id}}> </i>
			{{ line.text }}<br>
			</div>
			<div id="desc-s{{line.id}}">
			<div>
		{% elseif line.type == 't' %}
			</div>
			<div>
			<i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0> </i>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-toggle-down" id="b{{line.id}}" togg=true line={{line.id}}> </i>
			{{ line.text }}<br>
			</div>
			<div style="display: none" id="desc-s{{line.id}}">
		{% elseif line.type == 'c' %}
			<i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0></i>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-stop" id="r{{line.id}}" style="color:lightgray" line={{line.id}} rag=0> </i>
			{{ line.text }} [{{ line.rank }}]<br>
		{% else %}
			Dodgy line type!!!
		{% endif %}
	{% endfor %}
	</div>
	
	<p> </p>
{% endblock %}
