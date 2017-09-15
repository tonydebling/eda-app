{% extends 'templates/defaultdatatable.php' %}

{% block title %}Display Table{% endblock %}
{% block content %}
	<div class="w3-responsive">
	<h3>{{heading}}</h3>
	<table id="table_id" class="w3-table-all w3-small compact">
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
					{% if '<' in row[column] %}
						{{row[column]|raw}}
					{% else %}
						<td>{{row[column]|raw}}</td>
					{% endif %}
				{% endfor %}
		</tr>
		{% endfor %}
		</tbody>
	</table>
	</div>

{% endblock %}
	