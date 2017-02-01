app.controller("findschoolCtrl", function($scope, $http) {
	$scope.jsurl = '{{ url }}'; // filled in as TWIG variable
	$scope.myError = "Initial string";
	$scope.s = ["sA", "SB"];
	$scope.postcode = "jo";

    $http({
        method : "GET",
		url : $scope.jsurl
    }).then(function mySucces(response) {
        $scope.myData = response.data;
		$scope.myError = response.statusText;		
    }, function myError(response) {
        $scope.myError = response.statusText;
    });
		
	$scope.c = function(){
		return $scope.postcode.length;
	};

});
	

