{% extends 'templates/defaultjQ.php' %}

{% block title %}Checklist{% endblock %}

{% block content %}
	<script>
	$(document).ready(function(){
		
		$("i").on('click',function(){
			var id = $(this).attr('line');
			$('#desc-s'+id).toggle();
			$('#b-minus-s'+id).toggle();
			$('#b-plus-s'+id).toggle();
		});
		
	});
	</script>

	<div class="w3-responsive">
	<h3>{{subject}}</h3>
	{% for unit in units %}
		<div>
		<i class="fa fa-toggle-up" id="b-minus-s{{unit.line}}" line={{unit.line}}> </i>
		<i class="fa fa-toggle-down" style="display: none" id="b-plus-s{{unit.line}}" line={{unit.line}}></i>			
		{{ unit.name }}<br>
		</div>
		<div id="desc-s{{unit.line}}">
		{% for topic in unit.topics %}
			<div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<i class="fa fa-toggle-up" style="display: none" id="b-minus-s{{topic.line}}" line={{topic.line}}></i>
			<i class="fa fa-toggle-down" id="b-plus-s{{topic.line}}" line={{topic.line}}> </i>
			{{ topic.name }}<br>
			</div>
			<div style="display: none" id="desc-s{{topic.line}}">
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
