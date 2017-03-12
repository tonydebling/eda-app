{% extends 'templates/defaultjQ.php' %}

{% block title %}Checklist{% endblock %}

{% block content %}
	<div class="w3-responsive">
	<h3>{{subject}}</h3>
	{% for unit in units %}
		<script>
		$(document).ready(function(){
			$("#b-plus-s{{unit.number}}").hide();
			$("#b-minus-s{{unit.number}}").click(function(){
				$("#b-plus-s{{unit.number}}").toggle();
				$("#b-minus-s{{unit.number}}").toggle();
				$("#desc-s{{unit.number}}").toggle();
			});
			$("#b-plus-s{{unit.number}}").click(function(){
				$("#b-plus-s{{unit.number}}").toggle();
				$("#b-minus-s{{unit.number}}").toggle();
				$("#desc-s{{unit.number}}").toggle();
			});			
		});
		</script>
		<div>
		<i class="fa fa-minus-square" class="sb-s{{unit.number}}" id="b-minus-s{{unit.number}}" ></i>
		<i class="fa fa-plus-square" class="sb-s{{unit.number}}" id="b-plus-s{{unit.number}}" ></i>
		{{ unit.name }}<br>
		</div>
		<div id="desc-s{{unit.number}}">
		{% for topic in unit.topics %}
			<script>
				$(document).ready(function(){
					$("#desc-s{{unit.number}}-{{topic.number}}").hide();;
					$("#b-minus-s{{unit.number}}-{{topic.number}}").hide();
					$("#b-minus-s{{unit.number}}-{{topic.number}}").click(function(){
						$("#b-plus-s{{unit.number}}-{{topic.number}}").toggle();
						$("#b-minus-s{{unit.number}}-{{topic.number}}").toggle();
						$("#desc-s{{unit.number}}-{{topic.number}}").toggle();
					});
					$("#b-plus-s{{unit.number}}-{{topic.number}}").click(function(){
						$("#b-plus-s{{unit.number}}-{{topic.number}}").toggle();
						$("#b-minus-s{{unit.number}}-{{topic.number}}").toggle();
						$("#desc-s{{unit.number}}-{{topic.number}}").toggle();
					});	
				});
			</script>
			<div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-minus-square" id="b-minus-s{{unit.number}}-{{topic.number}}" ></i>
			<i class="fa fa-plus-square" id="b-plus-s{{unit.number}}-{{topic.number}}" ></i>
			{{ topic.name }}<br>
			</div>
			<div id="desc-s{{unit.number}}-{{topic.number}}">
				{% for check in topic.checks %}
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{ check.text }} [{{ check.rank }}]<br>
				{% endfor %}
			</div>
		{% endfor %}
		</div>
	{% endfor %}
	</div>
{% endblock %}
