{% extends 'templates/default.php' %}

{% block title %}School{% endblock %}
{% block content %}
	<form action="{{ urlFor('findschool.post')}}" method="post" autocomplete="off">
		<div>
			<label for"postcode">Enter school postcode</label>
			<input type="text" name="postcode" id="postcode" {% if request.post('postcode') %} value={{ request.post('identifier') }}{% endif %}>
			{% if errors.first('postcode') %} {{ errors.first('postcode') }} {% endif %}
		</div>

		<div>
			<input type="submit" value="Enter" >
		</div>
		<a href="{{ urlFor('stulogin') }}">Are you a student?</a>

		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
