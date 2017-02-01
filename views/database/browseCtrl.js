app.controller("browseCtrl", function($scope, $http) {
	$scope.jsurl = '{{ url }}'; // filled in as TWIG variable
	$scope.myError = "Initial string";
    $http({
        method : "GET",
		url : $scope.jsurl
    }).then(function mySucces(response) {
        $scope.myData = response.data;
		$scope.myError = response.statusText;
    }, function myError(response) {
        $scope.myError = response.statusText;
    });
});
