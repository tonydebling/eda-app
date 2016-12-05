{% extends 'templates/default.php' %}

{% block title %}DisplayBlocks{% endblock %}
{% block content %}
	<div class="w3-responsive">
	<h1>{{heading}}</h1>
	{% for block in blocks %}
		<h2>{{block.heading}}</h2>
		<table class="w3-table-all">
			<thead>
			<tr>
			{% for column in block.columns %}
				<th>{{ column }}</th>
			{% endfor %}
			</tr>
			</thead>
			<tbody>
			{% for row in block.table %}
			<tr>
				{% for column in block.columns %}
				<td>{{row[column]}}</td>
				{% endfor %}
			</tr>
			{% endfor %}
			</tbody>
		</table>
	{% endfor %}
	</div>

{% endblock %}
