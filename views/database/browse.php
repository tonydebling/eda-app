{% extends 'templates/defaultng.php' %}

{% block title %}Browse{% endblock %}
{% block content %}
	{% verbatim %}
	<div class="w3-responsive" ng-app="browseApp" ng-controller="browseCtrl">
	<h1>Angular Script</h1>
	{{ jsurl }}
	<table class="w3-table-all">
		<thead>
		<tr>
			<th>Id</th>
			<th>Tricode</th>
			<th>Name</th>
		</tr>
		</thead>
		<tbody>
		<tr ng-repeat="x in myData">
			<td>{{x.id}}</td>
			<td>{{x.tricode}}</td>
			<td>{{x.name}}</td>
		</tr>
		</tbody>
	</table>
	</div>
	{% endverbatim %}
	<script>
	{% include; 'database/browseApp.js' %}
	</script>
	<script>
	{% include; 'database/browseCtrl.js' %}
	</script>

{% endblock %}
