{% extends 'email/templates/default.php' %}

{% block content %}
	<p>You requested a password change!</p>
	<p> Reset your password using this link:
	{{ baseUrl }}{{ urlFor('resetpassword') }}?email={{ user.email }}&identifier={{ identifier|url_encode }}
	</p>
{% endblock %}