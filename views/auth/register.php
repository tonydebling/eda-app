{% extends 'templates/default.php' %}

{% block title %}Register{% endblock %}
{% block content %}
	<div>
	<h1>{{ name }}</h1>
	URN: {{ school_id }} <br>
	<a href="{{ urlFor('register') }}">Click if this is not your school?</a>
	<hr>
	</div>
	<form action="{{ urlFor('register.post')}}" method="post" autocomplete="off">

		<div>
			<label for"email">Email</label>
			<input type="text" name="email" id="email" {% if request.post('email') %} value={{ request.post('email') }}{% endif %}>
			{% if errors.first('email') %} {{ errors.first('email') }} {% endif %}
		</div>
		<div>
			<label for"username">Username</label>
			<input type="text" name="username" id="username" {% if request.post('username') %} value={{ request.post('username') }}{% endif %}>
			{% if errors.first('username') %} {{ errors.first('username') }} {% endif %}
		</div>
		<div>
			<label for"password">Password</label>
			<input type="password" name="password" id="password">
			{% if errors.first('password') %} {{ errors.first('password') }} {% endif %}
		</div>
		<div>
			<label for"password_confirm">Confirm password</label>
			<input type="password" name="password_confirm" id="password_confim">
			{% if errors.first('password_confirm') %} {{ errors.first('password_confirm') }} {% endif %}
		</div>
		<div>
			<input type="submit" value="Register" >
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
		<input type="hidden" name="school_id" value="{{ school_id }}">
		<input type="hidden" name="name" value="{{ name }}">
	</form>
{% endblock %}
	