{% extends 'templates/default.php' %}

{% block title %}{{ user.getFullNameOrUsername() }}{% endblock %}
{% block content %}
	<h1>{{ user.getFullName() }}</h1>

	<dl>
	{% if user.getFullName() %}
		<dt>Full name</dt>
		<dd>{{ user.getFullName() }}</dd>
	{% endif %}
	<dt>Email</dt>
	<dd>{{ user.email }}</dd>
	</dl>	
	{% for classe in classes %}
		<div>
			Class {{ classe.school_classe_id }}
		</div>
	{% endfor %}
{% endblock %}
