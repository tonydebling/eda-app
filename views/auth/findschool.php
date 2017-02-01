{% extends 'templates/defaultng.php' %}

{% block title %}School{% endblock %}
{% block content %}

	<div class="w3-responsive" ng-app="findschoolApp" ng-controller="findschoolCtrl">
	<form action="{{ urlFor('findschool.post')}}" method="post" autocomplete="off">
		<div>
			<label for"postcode">Enter school postcode</label>
			<input type="text" name="postcode" ng-model="postcode" {% if request.post('postcode') %} value={{ request.post('identifier') }}{% endif %}>
			{% if errors.first('postcode') %} {{ errors.first('postcode') }} {% endif %}
		</div>

		<div>
			<input type="submit" value="Enter" >
		</div>
		<a href="{{ urlFor('stulogin') }}">Are you a student?</a>

		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>


	{% verbatim %}
	<h1>Angular Script</h1>
	{{ jsurl }} <br>
	{{ postcode}} <br>
	{{myData[0]}} <br>
	{{(myData|searchFor:postcode).length}} <br>
	{{ myError}} <br>

	<table display:block; width: 100%;>
		<thead display: inline-block; width: 100%; height: 20px;>
		<tr>
			<th>School id</th>
			<th>Postcode</th>
			<th>Name</th>
		</tr>
		</thead>
		<tbody height:500px; display:inline-block; width: 100%; overflow-y: scroll;>
		<tr ng-repeat="x in myData | searchFor:postcode">
			<td>{{ x.id }}</td>
			<td>{{ x.postcode }}</td>
			<td>{{ x.name }}</td>
		</tr>
		</tbody>
	</table>

	</div>
	{% endverbatim %}
	<script>
	{% include 'auth/findschoolApp.js' %}
	</script>
	<script>
	{% include 'auth/findschoolCtrl.js' %}
	</script>

{% endblock %}
