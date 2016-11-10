{% extends 'templates/default.php' %}

{% block title %}Edanalytics upload{% endblock %}
{% block content %}
	<form action="{{ urlFor('eda-upload.post')}}" method="post" enctype="multipart/form-data" autocomplete="off">
		<div>
			<label for"fileType">File type</label>
			<select name="fileType">
				<option value="schools">Schools</option>	
			</select>
		</div>
		<div>
            <p>
			<label>Select image to upload:</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
			</p>
		</div>
		<div>
			<input type="submit" value="Upload" >
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
