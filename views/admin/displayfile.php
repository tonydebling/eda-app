{% extends 'templates/default.php' %}

{% block title %}Display Table{% endblock %}
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
	