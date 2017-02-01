{% extends 'templates/default.php' %}

{% block title %}Display Collection{% endblock %}
{% block content %}
	<div class="w3-responsive">
	<h1>{{heading}}</h1>
	<table class="w3-table-all">
		<thead>
		<tr>
		{% for column in collection.toArray() %}
			<th>{{ column }}</th>
		{% endfor %}
		</tr>
		</thead>
		<tbody>
		{% for row in collection.all() %}
		<tr>
			{% for column in collection.keys() %}
			<td>{{row[column]}}</td>
			{% endfor %}
		</tr>
		{% endfor %}
		</tbody>
	</table>
	</div>

{% endblock %}
