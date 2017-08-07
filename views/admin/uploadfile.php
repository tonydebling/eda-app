{% extends 'templates/default.php' %}

{% block title %}Upload file{% endblock %}
{% block content %}
	<form action="{{ urlFor('uploadfile.post')}}" method="post" enctype="multipart/form-data" autocomplete="off">
		<div>
			<label for"fileType">File type</label>
			<select name="fileType">
				<option value="students">Students</option>
                <option value="staffmembers">Staffmembers</option>
				<option value="subjects">Subjects</option>
				<option value="classes">Classes</option>
				<option value="student_classes">Student Classes</option>
				<option value="checkpoints">Checkpoints</option>
				<option value="testpoints">Testpoints</option>
				<option value="testresults">Testresults</option>
			</select>
		</div>
		<div>
            <p>
			<label>Select file to upload:</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
			</p>
            <p>
			<label>Testpoint identifier:</label>
			<input type="number" name="testpoint_id" id="testpoint_id">
			</p>
		</div>
		<div>
			<input type="submit" value="Upload" >
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
