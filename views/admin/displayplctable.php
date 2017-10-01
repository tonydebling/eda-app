{% extends 'templates/default.php' %}

{% block title %}Checklist Table{% endblock %}

{% block content %}
	<script>

	$(document).ready(function(){

		var subject_id = '{{ subject_id }}';
		var subject_name = '{{ subject_name }}';
		var checklist_id = '{{ checklist_id }}';

		var jsonLookUpTable = '{{ jsonLookUpTable|raw}}';
		var lookUp = JSON.parse(jsonLookUpTable);
        var jsonPlcTable = '{{ jsonPlcTable|raw}}';
        var plcTable = JSON.parse(jsonPlcTable);

        var ratingsTable = [];
        for (i = 0; i < plcTable.length; i++){
            ratingsTable[i] = [];
            if (plcTable[i]['ratings'].length == 0){
                for (j = 0; j < lookUp.length; j++){
                    ratingsTable[i][j] = 0;
                }
            } else if (plcTable[i]['ratings'].length == lookUp.length){
                for (j = 0; j < plcTable[i]['ratings'].length; j++){
                    ratingsTable[i].push(Number(plcTable[i]['ratings'][j]));
                }
            } else {
                alert('Odd ratings length');
            }
        }

		$(document).ajaxError(function(event, jqXHR, settings, thrownError){
			alert(thrownError);
		});
		
		function isHot(row, line){
			return (Number(ratingsTable[row][line]) > 3);
        }
        function setHot(row, line){
			if (!isHot(row, line)) {ratingsTable[row][line] +=4;}
        }
        function clearHot(row, line){
			if (isHot(row, line)) {ratingsTable[row][line] -=4;}
        }
		
		function updatePlcRecord(row){
			ratingsContent = String(ratingsTable[row]).replace(/,/g,"");
			plc_id = plcTable[row]['plc_id']
            if (plc_id == 0){
			    alert('plc_id is zero')
            } else {
                var updateUrl = "updateplcrecord?id="+plc_id;
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
        }

        // Colour the initial tree
		$("i.fa-fire").each(function(index) {
			var line = $(this).attr('line');
            var row = $(this).attr('row');
			if (isHot(row, line)) {
				$(this).css("color","red");
            }
            $(this).attr("up", lookUp[line]['parent']);
		});

		$("i.fa-stop").each(function(index) {
			var line = $(this).attr('line');
            var row = $(this).attr('row');
			ragvalue = ratingsTable[row][line];
			if (isHot(row, line)){
				ragvalue -=4;
            }
            if (ragvalue == 0) {
	//			$(this).css("color","lightgray");
			} else if (ragvalue == 1){
				$(this).css("color","red");
			} else if (ragvalue == 2){
				$(this).css("color","orange");
			} else {
				$(this).css("color","green");
            }
        });

		$("[togg]").on('click',function(){
			var line = $(this).attr('line');
            var row = $(this).attr('row');
			$('#desc-s'+line).toggle(); // TO BE FIXED
			$('#b'+line).toggleClass("fa-toggle-down fa-toggle-up");
		});

		$("[rag]").on({
			click: function(){
				var line = $(this).attr('line');
                var row = $(this).attr('row');
				ragvalue = ratingsTable[row][line];
				if (isHot(row, line)) {
					ragvalue -=4;
                }
                if (ragvalue == 0) {
					$(this).css("color","red");
					ratingsTable[row][line] +=1;
				} else if (ragvalue == 1){
					$(this).css("color","orange");
					ratingsTable[row][line] +=1;
				} else if (ragvalue == 2){
					$(this).css("color","green");
					ratingsTable[row][line] +=1;
				} else {
					$(this).css("color","red");
					ratingsTable[row][line] -=2;
                }
                updatePlcRecord(row);
			},
			dblclick: function() {
				var line = $(this).attr('line');
                var row = $(this).attr('row');
				if (isHot(row, line)) {
					ratingsTable[row][line] = 7;
				} else {
					ratingsTable[row][line] = 3;
                }
                $(this).css("color","green");
				updatePlcRecord(row);
			}
		});
		
		$("i.fa-fire").on('click',function(){
			var line = $(this).attr('line');
            var row = $(this).attr('row');
			if (lookUp[line]['nodetype'] != 'c'){
				return;
            }
            if (isHot(row, line)) {
				$(this).css("color","lightgray");
				clearHot(row, line);
				var topic = lookUp[line]['parent'];
				var unit = lookUp[topic]['parent'];
				var allClear = true;
				i = topic + 1;
				while (lookUp[i]['nodetype'] == 'c') {
					if (isHot(row, i)) {
						allClear = false;
					}
					i += 1;
                    if (i == maxIndexPlusOne) {
                        break;
                    }
                }
                if (allClear) {
					clearHot(row, topic);
					$("#h"+row+"x"+topic).css("color","lightgray");
					allClear = true;
					i = unit + 1;
					while (lookUp[i]['nodetype'] != 'u') {
						if (isHot(row, i)) {
							allClear = false;
						}
						i += 1;
                        if (i == maxIndexPlusOne) {
                            break;
                        }
                    }
                    if (allClear){
						clearHot(row, unit);
						$("#h"+row+"x"+unit).css("color","lightgray");
						}
				}
			} else {
				$(this).css("color","red");
				setHot(row, line);
				var topic = lookUp[line]['parent'];
				$("#h"+row+"x"+topic).css("color","red");
				setHot(row, topic);
				var unit = lookUp[topic]['parent'];
				setHot(row, unit);
				$("#h"+row+"x"+unit).css("color","red");
            }
            updatePlcRecord(row);
		});
	
	});
	
	</script>


    <h3> {{subject_name}} Checklist</h3>

<div class="w3-container ask-matrix">
	<table class="w3-responsive w3-border ">

	{% for line in checklist %}
		{% if line.nodetype == 'r' %}
        <thead>
        <tr>
			<th class="ask-matrix-row">{{subject_name}} Checklist</th>
            {% for row in plcTable %}
            <th class="ask-matrix-col-cell"><div class="ask-matrix-col-text">{{ row.name }}</div></th>
            {% endfor %}
        </tr>
        </thead>
        <tbody>
		{% elseif line.nodetype == 'u'%}
        <tr>
            <td class="ask-matrix-row" ><i class="fa fa-toggle-up" id="b{{line.id}}" togg=true line={{line.id}}></i>&nbsp;{{ line.text }}</td>
            {% for row in plcTable %}
			    <td><div><i class="fa fa-fire" style="color:lightgray" id="h{{row.index}}x{{line.id}}" line={{line.id}} row={{row.index}} up=0></i></div></td>
            {% endfor %}
        </tr>
		{% elseif line.nodetype == 't' %}
        <tr>
            <td class="ask-matrix-row">&nbsp<i class="fa fa-toggle-down" id="b{{line.id}}" togg=true line={{line.id}}> </i>&nbsp;{{ line.text }}</td>
            {% for row in plcTable %}
                <td><i class="fa fa-fire" style="color:lightgray" id="h{{row.index}}x{{line.id}}" line={{line.id}} row={{row.index}} up=0></i></td>
            {% endfor %}
        </tr>
		{% elseif line.nodetype == 'c' %}
        <tr>
            <td class="ask-matrix-row">{{ line.text }}</td>
            {% for row in plcTable %}
                <td><div>
                    <i class="fa fa-fire" style="color:lightgray" id="h{{row.index}}x{{line.id}}" line={{line.id}} row={{row.index}} up=0></i>
                    <i class="fa fa-stop" id="r{{line.id}}" style="color:lightgray" line={{line.id}} row={{row.index}} rag=0> </i>
                </div> </td>
            {% endfor %}
        </tr>
		{% else %}
			Dodgy line type!!!
		{% endif %}
	{% endfor %}
        </tbody>
	</table>
</div>
	<p> </p>
{% endblock %}
