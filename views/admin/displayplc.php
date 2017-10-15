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

        $("#desc").hide();
        // Colour the initial tree
		$("i.fa-fire").each(function(index) {
			var line = $(this).attr('line');
			if (isHot(line)) {
				$(this).css("color","red");
            }
            $(this).attr("up", lookUp[line]['parent']);
		});
        // set up the RAG colors
		$("i.fa-stop").each(function(index) {
			var line = $(this).attr('line');
			var ragvalue = ratings[line];
			if (isHot(line)) {
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
        // Set up descendants in check lines
		$("tr.desc").each(function(index){
            var line = $(this).attr('line');
            var up = lookUp[line]['parent'];
            var desc = "";
            if (up == 0){
                $(this).css("font-weight", "bold");
            }
            while (up != 0) {
                desc = "d" + up;
                $(this).addClass(desc);
                up = lookUp[up]['parent'];
            };
        });
        resetTree(0);
        $("#resourcelist").hide();
        $("#display").show();

        function resetTree(line){
            // if node is not a leaf then ...
            if (lookUp[line]['child']>0){
                // if node is hidden then ...
                if (ratings[line] % 4 != 0){
                    $('#b'+ line).toggleClass("fa-toggle-down fa-toggle-up");
                    $(".d" + line).hide();
                }
                var node = lookUp[line]['child'];
                while (node > 0){
                    resetTree(node);
                    node = lookUp[node]['next'];
                }
            }
        };

		function showVisibleTree(line){
            $('#n'+ line).show();
            // if node is not a leaf, and is visible then ...
            if ((lookUp[line]['child']>0)&&(ratings[line] % 4 == 0)){
                var node = lookUp[line]['child'];
                while (node > 0){
                    showVisibleTree(node);
                    node = lookUp[node]['next'];
                }
            }
        };

		$("[togg]").on('click',function(){
			var line = $(this).attr('line');
			if (ratings[line] % 4 == 0) {
                $(".d" + line).hide();
                ratings[line] += 1;
            } else {
                ratings[line] -= 1;
                showVisibleTree(line);
            }
			$('#b'+ line).toggleClass("fa-toggle-down fa-toggle-up");
            updatePlcRecord();
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

        $(".check-item").on('click',function(){
            var line = $(this).attr('line');
            $("#current-item").text($(this).text());
            $("#current-search").text(lookUp[line]['search']);
            var searchString = lookUp[line]['search'];
            searchStringEncoded = encodeURIComponent(searchString);
            if (searchString.length > 1){
                // update and display list of resources
                $("#resourcelist").hide();
                $("li").remove("");
                var url = 'getdata/resources?sstr="'+searchStringEncoded+'"';
                $.getJSON(url, function(data){
                    data.forEach(function(resource){
                        listElement = '<li id="'+resource['id'] + '" resourceUrl="' + resource['url'] +'">'
                            +'<i class="fa fa-play"></i>&nbsp;'
                            +resource['title']
                            +'</li>';
                        $("#resourcelist").append(listElement);
                    });
                    $("li").on('click',function(){
                        resourceUrl = $(this).attr('resourceUrl');
                        window.open("http://"+resourceUrl);
                    });
                });
                $("#resourcelist").show();
            } else {
                // hide list of schools
                $("#resourcelist").hide();
                $("li").remove("");
            }
        });
	});
	
	</script>

<div id="display" class="w3-row">
    <div class="w3-container w3-col s6 w3-hoverable">
        <h3>Checklist</h3>
        <table>
        {% for line in checklist %}
        {% if line.nodetype == 'r' %}

        {% elseif line.nodetype == 'c' %}
        <tr class="desc" id="n{{line.id}}" line="{{line.id}}">
            <td><i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0></i></td>
            <td></td>
            <td><i class="fa fa-stop" id="r{{line.id}}" style="color:lightgray" line={{line.id}} rag=0> </i></td>
            <td class="ask-check check-item" line={{line.id}}>{{ line.text }}</td>
            <td>{{ line.rank }}</td>
        </tr>

        {% else %}
        <tr class="desc" id="n{{line.id}}" line="{{line.id}}">
            <td><i class="fa fa-fire" style="color:lightgray" id="hot{{line.id}}" line={{line.id}} up=0></i></td>
            <td><i class="fa fa-toggle-up" id="b{{line.id}}" togg=true line={{line.id}}> </i></td>
            <td colspan="2">{{ line.text }}</td>
            <td></td>
        </tr>

        {% endif %}
        {% endfor %}
        </table>
    </div>

    <div class="w3-container w3-col s6">
        <h3> Learning Resources</h3>
        <h4>Checklist Item</h4>
        <div id="current-item"></div>
        <h4>Search</h4>
        <div id="current-search"></div>
        <h4>Resources</h4>

        <ul class="w3-ul w3-border w3-hoverable" id="resourcelist">
        </ul>
    </div>
</div>

<p> </p>
{% endblock %}
