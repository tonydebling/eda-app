{% extends 'templates/default.php' %}

{% block title %}{{ user.getFullNameOrUsername() }}{% endblock %}
{% block content %}
	<h3>Subject Dashboard</h3>
	<div class="w3-responsive">
		<table class="w3-table-all w3-third">
			<thead>
			<tr>
				<td>Subject</td>
				<td>Checklist</td>
				<td>Results</td>
				<td>Targets</td>
			</tr>
			</thead>
			<tbody>
			{% for line in dash %}
			<tr>
				<td>{{ line.subject }}</td>
				<td class="w3-center">
				{% if (line.plc_id != null) %}
					<a href="{{ urlFor('plc') }}?id={{ line.plc_id}}"
					class="fa fa-check-square" style="color:green" ></a>
				{% endif %}
				</td>
				<td></td>
				<td></td>
			</tr>
			{% endfor %}
			<tr>
				<td>Progress 8</<td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			</tbody>
		</table>
	</div>
{% endblock %}
