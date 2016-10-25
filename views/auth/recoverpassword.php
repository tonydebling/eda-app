{% extends 'templates/default.php' %}

{% block title %}Recover password{% endblock %}
{% block content %}
	<form action="{{ urlFor('recoverpassword.post')}}" method="post" autocomplete="off">
		<div>
			<label for"email">Email</label>
			<input type="text" name="email" id="email" {% if request.post('email') %} value={{ request.post('email') }}{% endif %}>
			{% if errors.first('email') %} {{ errors.first('email') }} {% endif %}
		</div>
		<div>
			<input type="submit" value="Request reset" >
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
	