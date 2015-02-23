var app = angular.module('app', []);

app.controller('HangmanController', ['$scope', 'HangmanService', function($scope, HangmanService) {
    $scope.gameId = null;
    $scope.word = "";
    $scope.character = "";
    $scope.attempt = 0;
    $scope.wrongChars = "";

    $scope.newGame = function() {
        HangmanService.newWord().then(function(response) {
            $scope.gameId = response.data.id;
            $scope.word = "";
            $scope.attempt = 0;
            $scope.character = "";
            $scope.characters = [];
            $scope.wrongChars = "";

            for (var x = 0; x < response.data.length; x++) {
                $scope.word += "_ ";
            }
        }, function() {
            alert("Start a new game failed!");
        });
    };

    $scope.$watch('attempt', function (newAttempt) {
        if (newAttempt == 6) {
            alert("Sorry, You die!");
        }
    });

    $scope.guessButton = function () {
        HangmanService.updateGame($scope.gameId, $scope.character).then(
            function (response) {
                $scope.updateStatus(response.data);
            },
            function (response) {
                alert(response.data.message);
            }
        );
    };

    $scope.updateStatus = function (data) {
        if (data.guessed) {
            data.positions.forEach(function(value) {
                var position = value * 2;
                var word = $scope.word;
                $scope.word = word.substring(0, position) + $scope.character + word.substring(position + 1);
            });
        }

        HangmanService.getStatus($scope.gameId).then(
            function(response) {
                $scope.attempt = 6 - response.data.tries_left;
                $scope.wrongChars = response.data.wrong_chars.join(' ');
            }
        );

        $scope.character = "";
    };
}]);