{% extends 'templates/default.php' %}

{% block title %}Checklist{% endblock %}


{% block content %}
	<script>
	$(document).ready(function(){

		var jsonSubjectList = '{{ jsonsubjectlist|raw}}';
		var subjectList = JSON.parse(jsonSubjectList);

		for (i=0; i < subjectList.length; i++){
		    for (j=0; j < subjectList[i]['hotlist'].length; j++){
                subjectList[i]['hotlist'][j]['loaded'] = false;
                $("#rl"+i+"x"+j).hide();
            }
        }


		$(document).ajaxError(function(event, jqXHR, settings, thrownError){
			alert(thrownError);
		});
		
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

		$("[stogg]").on('click',function(){
			var index = $(this).attr('index');
			$('#t'+ index).toggleClass("fa-toggle-down fa-toggle-up");
            $('#b'+ index).toggle()
		});

		$("[rag]").on({
			click: function(){
				var id = $(this).attr('line');
				var ragvalue = ratings[id];
                ragvalue -=4;

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
                ratings[id] = 7;
                $(this).css("color","green");
				updatePlcRecord();
			}
		});

        $(".check-item").on('click',function(){
            var sub = $(this).attr('sub');
            var chk = $(this).attr('chk');
            // Check to see if resource list is visible
            if ($("#rl"+sub+"x"+chk).is(":visible")){
                // hide list of resources
                $("#rl"+sub+"x"+chk).hide();
            } else {
                // if not loaded search for resources, add to list and show
                if (!subjectList[sub]['hotlist'][chk]['loaded']){
                    $("#sp"+sub+"x"+chk).show();
                    var searchString = subjectList[sub]['hotlist'][chk]['search'];
                    searchStringEncoded = encodeURIComponent(searchString);
                    var url = 'getdata/resources?sstr="'+searchStringEncoded+'"';
                    $.getJSON(url, function(data){
                        data.forEach(function(resource){
                            listElement = '<li id="'+resource['id'] + '" resourceUrl="' + resource['url'] +'">'
                                +'<i class="fa fa-play"></i>&nbsp;'
                                +resource['title']
                                +'</li>';
                            $("#rl"+sub+"x"+chk).append(listElement);
                        });
                        $("li").on('click',function(){
                            resourceUrl = $(this).attr('resourceUrl');
                            window.open("http://"+resourceUrl);
                        });
                        subjectList[sub]['hotlist'][chk]['loaded'] = true;
                        $("#sp"+sub+"x"+chk).hide();
                    });

                }
                $("#rl"+sub+"x"+chk).show();
            }

        });
	});
	
	</script>

<div id="display" class="w3-row">
    <div class="w3-hoverable">
        <h3>Your hot lists</h3>
        {% for subject in subjectlist %}
           <div class="w3-orange w3-hover-green" id="n{{subject.index}}" index="{{subject.index}}">
               {% if subject.plc_id != null  %}
               <i class="fa fa-toggle-down" id="t{{subject.index}}" stogg=true index="{{subject.index}}"></i>
               {% else %}
               &nbsp;&nbsp;&nbsp;
               {% endif %}
               {{ subject.name }}
           </div>
            <div id="b{{subject.index}}" style="display: none" >
                   {% for item in subject.hotlist %}
                   <div>
                       <i class="fa fa-toggle-down check-item" id="t{{subject.index}}x{{item.index}}" ctogg=true sub="{{subject.index}}"  chk="{{item.index}}"></i>
                       {{ item.text }}
                       <div id="sp{{subject.index}}x{{item.index}}" style="display: none">
                           <i class="fa fa-spinner fa-spin"></i>
                       </div>
                       <ul class="w3-ul w3-border w3-hoverable" id="rl{{subject.index}}x{{item.index}}">
                       </ul>
                   </div>

                   {% endfor %}
            </div>

        {% endfor %}

    </div>

    <div class="w3-container w3-col s6">

    </div>
</div>

<p> </p>
{% endblock %}
