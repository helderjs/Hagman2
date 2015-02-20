app.service('HangmanService', ["$http", function ($http) {
    this.newWord = function () {
        return $http.get("/tmp");
    };

    this.verifyCharacter = function (char) {
        var result = false;

        return $http.post('/tmp', $.param({char: char})).then(function () {
            return result;
        }, function () {
            return result;
        });
    };
}]);