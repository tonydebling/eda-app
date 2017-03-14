{% extends 'templates/defaultjQ.php' %}

{% block title %}Checklist{% endblock %}

{% block content %}
	<div class="w3-responsive">
	<h3>{{subject}}</h3>
	{% for unit in units %}
		<script>
		$(document).ready(function(){
			$("#b-plus-s{{unit.line}}").hide();
			$("#b-minus-s{{unit.line}}").click(function(){
				$("#b-plus-s{{unit.line}}").toggle();
				$("#b-minus-s{{unit.line}}").toggle();
				$("#desc-s{{unit.line}}").toggle();
			});
			$("#b-plus-s{{unit.line}}").click(function(){
				$("#b-plus-s{{unit.line}}").toggle();
				$("#b-minus-s{{unit.line}}").toggle();
				$("#desc-s{{unit.line}}").toggle();
			});			
		});
		</script>
		<div>
		<i class="fa fa-minus-square" class="sb-s{{unit.line}}" id="b-minus-s{{unit.line}}" ></i>
		<i class="fa fa-plus-square" class="sb-s{{unit.line}}" id="b-plus-s{{unit.line}}" ></i>
		{{ unit.name }}<br>
		</div>
		<div id="desc-s{{unit.line}}">
		{% for topic in unit.topics %}
			<script>
				$(document).ready(function(){
					$("#desc-s{{topic.line}}").hide();;
					$("#b-minus-s{{topic.line}}").hide();
					$("#b-minus-s{{topic.line}}").click(function(){
						$("#b-plus-s{{topic.line}}").toggle();
						$("#b-minus-s{{topic.line}}").toggle();
						$("#desc-s{{topic.line}}").toggle();
					});
					$("#b-plus-s{{topic.line}}").click(function(){
						$("#b-plus-s{{topic.line}}").toggle();
						$("#b-minus-s{{topic.line}}").toggle();
						$("#desc-s{{topic.line}}").toggle();
					});	
				});
			</script>
			<div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-minus-square" id="b-minus-s{{topic.line}}" ></i>
			<i class="fa fa-plus-square" id="b-plus-s{{topic.line}}" ></i>
			{{ topic.name }}<br>
			</div>
			<div id="desc-s{{topic.line}}">
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
