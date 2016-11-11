{% extends 'templates/default.php' %}

{% block title %}All users{% endblock %}
{% block content %}
	<h2>All users</h2>
	
	{% if users is empty %}
		<p>No registered users.</p>
	{% else %}
		{% for user in users %}
			<div class="user">
				<a href="{{ urlFor('user.profile', {user_id: user.id}) }}">
				{% if user.getFullName() %}
				 {{ user.getFullName() }}
				{% endif %}
				{% if user.is_student %}
					(Student)
				{% endif %}
			</div>
		{% endfor %}
	{% endif %}
{% endblock %}
