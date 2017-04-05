{% extends 'templates/defaultjQ.php' %}

{% block title %}{{ user.getFullNameOrUsername() }}{% endblock %}
{% block content %}
	<h2>Subject Dashboard</h2>
	{% for line in dash %}
		<div>
			{{ line.subject }}
				{% if (line.plc_id != null) %}
					<a href="{{ urlFor('plc', {plc_id: line.plc_id}) }}"
					class="fa fa-check-square" style="color:green" ></a>
					{{ line.plc_id }}
				{% endif %}
		</div>
	{% endfor %}
{% endblock %}
