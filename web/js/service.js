app.service('HangmanService', ["$http", function ($http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    this.newWord = function () {
        return $http.post("/games");
    };

    this.updateGame = function (id, char) {
        return $http.post('/games/' + id, $.param({char: char}));
    };

    this.getStatus = function (id) {
        return $http.get('/games/' + id);
    };
}]);