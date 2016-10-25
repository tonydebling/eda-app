{% extends 'templates/default.php' %}

{% block title %}{{ user.getFullNameOrUsername() }}{% endblock %}
{% block content %}
	<h2>{{ user.username }}</h2>
	<dl>
	{% if user.getFullName() %}
		<dt>Full name</dt>
		<dd>{{ user.getFullName() }}</dd>
	{% endif %}
	<dt>Email</dt>
	<dd>{{ user.email }}</dd
	</dl>	
	
{% endblock %}
