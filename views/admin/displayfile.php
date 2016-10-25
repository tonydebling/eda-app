{% extends 'templates/default.php' %}

{% block title %}Upload file{% endblock %}
{% block content %}
	<div class="w3-responsive">
	<h1>{{heading}}</h1>
	<table class="w3-table-all">
		<thead>
		<tr>
		{% for column in columns %}
			<th>{{ column }}</th>
		{% endfor %}
		</tr>
		</thead>
		<tbody>
		{% for row in table %}
		<tr>
			{% for column in columns %}
			<td>{{row[column]}}</td>
			{% endfor %}
		</tr>
		{% endfor %}
		</tbody>
	</table>
	</div>

{% endblock %}
	