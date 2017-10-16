{% extends 'templates/defaultslick.php' %}

{% block title %}Checklist{% endblock %}


{% block content %}
	<script>
	$(document).ready(function(){

	    var sliderCss = {arrows: true, dots: true, slidesToShow: 4, slidesToScroll: 4, responsive: [
                {breakpoint: 1024, settings: {slidesToShow: 3, slidesToScroll: 3, infinite: true, dots: true}},
                {breakpoint: 600, settings: {slidesToShow: 2, slidesToScroll: 2}},
                {breakpoint: 480, settings: {slidesToShow: 1, slidesToScroll: 1}}
                ]};
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

		function youTubeId(url){
		    // will need updating
		    var youTubeId = url.split('v=')[1];
		    var index = youTubeId.indexOf("&");
		    if (index > 0){
		        youTubeId = youTubeId.substring(0,index);
            }
            return youTubeId;
        }

        function youTubeThumbnail(url){
		    var thumbnail = "https://img.youtube.com/vi/"+youTubeId(url)+"/sddefault.jpg";
		    return thumbnail;
        }

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
                    $("#rl"+sub+"x"+chk).slick(sliderCss);
                    var searchString = subjectList[sub]['hotlist'][chk]['search'];
                    searchStringEncoded = encodeURIComponent(searchString);
                    var url = 'getdata/resources?sstr="'+searchStringEncoded+'"';
                    $.getJSON(url, function(data){
                        data.forEach(function(resource){
                            listElement = '<div class="w3-display-container" id="'+resource['id'] + '" resourceUrl="' + resource['url'] +'">'
                                +'<img class="w3-hover-opacity ask-slider-image"'+'" src="' + youTubeThumbnail(resource['url']) + '">'
                                +'<div class="w3-display-topleft w3-container"><i class="fa fa-video-camera"'+'" resourceUrl="' + resource['url'] + '"></i></div>'
                                +'<div class="w3-display-bottomleft w3-container">'+ resource['title'] + '</div>'
                                +'</div>';
                            $("#rl"+sub+"x"+chk).slick('slickAdd',listElement);
                        });

                        $(".fa-video-camera").on('click',function(){
                            var resourceUrl = $(this).attr('resourceUrl');
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
    <div class="w3-hoverable w3-container">
        <h3>Your hot lists</h3>
        {% for subject in subjectlist %}
           <div class="w3-orange w3-hover-green w3-container" id="n{{subject.index}}" index="{{subject.index}}">
               {% if subject.plc_id != null  %}
               <i class="fa fa-toggle-down" id="t{{subject.index}}" stogg=true index="{{subject.index}}"></i>
               {% else %}
               &nbsp;&nbsp;&nbsp;
               {% endif %}
               {{ subject.name }}
           </div>
            <div id="b{{subject.index}}" style="display: none" class="w3-container w3-green" >
                   {% for item in subject.hotlist %}
                    <div>
                       <i class="fa fa-toggle-down check-item" id="t{{subject.index}}x{{item.index}}" ctogg=true sub="{{subject.index}}"  chk="{{item.index}}"></i>
                       {{ item.text }}
                       <div id="sp{{subject.index}}x{{item.index}}" style="display: none">
                           <i class="fa fa-spinner fa-spin"></i>
                       </div>
                        <div class="w3-green w3-container">
                            <div id="rl{{subject.index}}x{{item.index}}" class="rl ask-slider-container"></div>
                        </div>

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

