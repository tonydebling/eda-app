{% extends 'templates/default.php' %}

{% block title %}Checklist{% endblock %}

{% block content %}
	<script>
	$(document).ready(function(){

		var plc_id = '{{ plc_id }}';
		var ratingsContent = '{{ ratings }}';
		var maxIndexPlusOne = ratingsContent.length;
		var ratings = [];
		for (i = 0; i < maxIndexPlusOne; i++){
			ratings.push(Number(ratingsContent[i]));
		}
		var jsonLookUpTable = '{{ jsonLookUpTable|raw}}';
		var lookUp = JSON.parse(jsonLookUpTable);
		
		$(document).ajaxError(function(event, jqXHR, settings, thrownError){
			alert(thrownError);
		});
		
		function isHot(line){
			return (Number(ratings[line]) > 3);
        }
        function setHot(line){
			if (!isHot(line)) {ratings[line] +=4;}
        }
        function clearHot(line){
			if (isHot(line)) {ratings[line] -=4;}
        }
        var request = null;
		
		function updatePlcRecord(){
			ratingsContent = String(ratings).replace(/,/g,"");
			var updateUrl = "updateplcrecord?id=".plc_id;
			data = 'plc_id='+plc_id+'&ratings='+ratingsContent;
			$.ajax({
				url: updateUrl,
				data: {plc_id: plc_id, ratings: ratingsContent},
				type: "GET",
				success: function(data){
//					alert("Success");
				},
			});
        }
        // Colour the initial tree
		$("i.fa-fire").each(function(index) {
			var line = $(this).attr('line');
			if (isHot(line)) {
				$(this).css("color","red");
            }
            $(this).attr("up", lookUp[line]['parent']);
		});

		$("i.fa-stop").each(function(index) {
			var id = $(this).attr('line');
			ragvalue = ratings[id];
			if (isHot(id)) {
				ragvalue -=4;
            }
            if (ragvalue == 0) {
				$(this).css("color","lightgray");
			} else if (ragvalue == 1){
				$(this).css("color","red");
			} else if (ragvalue == 2){
				$(this).css("color","orange");
			} else {
				$(this).css("color","green");
            }
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
                }
                if (ragvalue == 0) {
					$(this).css("color","red");
					ratings[id] +=1;
				} else if (ragvalue == 1){
					$(this).css("color","orange");
					ratings[id] +=1;
				} else if (ragvalue == 2){
					$(this).css("color","green");
					ratings[id] +=1;
				} else {
					$(this).css("color","red");
					ratings[id] -=2;
                }
                updatePlcRecord();
			},
			dblclick: function() {
				var id = $(this).attr('line');
				if (isHot(id)) {
					ratings[id] = 7;
				} else {
					ratings[id] = 3;
                }
                $(this).css("color","green");
				updatePlcRecord();
			}
		});
		
		$("i.fa-fire").on('click',function(){
			var line = $(this).attr('line');
			if (lookUp[line]['nodetype'] != 'c'){
				return;
            }
            if (isHot(line)) {
				$(this).css("color","lightgray");
				clearHot(line);
				var topic = lookUp[line]['parent'];
				var unit = lookUp[topic]['parent'];
				var allClear = true;
				i = topic + 1;
				while (lookUp[i]['nodetype'] == 'c') {
					if (isHot(i)) {
						allClear = false;
					}
					i += 1;
                    if (i == maxIndexPlusOne) {
                        break;
                    }
                }
                if (allClear) {
					clearHot(topic);
					$("#hot"+topic).css("color","lightgray");
					allClear = true;
					i = unit + 1;
					while (lookUp[i]['nodetype'] != 'u') {
						if (isHot(i)) {
							allClear = false;
						}
						i += 1;
                        if (i == maxIndexPlusOne) {
                            break;
                        }
                    }
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
            }
            updatePlcRecord();
		});
	
	});
	
	</script>

	<div class="w3-responsive">

	{% for line in checklist %}
		{% if line.nodetype == 'r' %}
			<h3> {{line.text}} Checklist</h3>
			<div><div>
		{% elseif line.nodetype == 'u'%}
			</div></div>
			<div>
			<i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0></i>
			<i class="fa fa-toggle-up" id="b{{line.id}}" togg=true line={{line.id}}> </i>
			{{ line.text }}<br>
			</div>
			<div id="desc-s{{line.id}}">
			<div>
		{% elseif line.nodetype == 't' %}
			</div>
			<div>
			<i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0> </i>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-toggle-down" id="b{{line.id}}" togg=true line={{line.id}}> </i>
			{{ line.text }}<br>
			</div>
			<div style="display: none" id="desc-s{{line.id}}">
		{% elseif line.nodetype == 'c' %}
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
