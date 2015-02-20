var app = angular.module('app', []);

app.controller('HangmanController', ['$scope', 'HangmanService', function($scope, HangmanService) {
    $scope.word = "";
    $scope.character = "";
    $scope.characters = [];
    $scope.attempt = 0;
    $scope.wrongChars = "";

    $scope.newGame = function() {
        HangmanService.newWord().then(function(response) {
            $scope.word = "";
            $scope.attempt = 0;
            $scope.character = "";
            $scope.characters = [];
            $scope.wrongChars = "";

            for (var x = 0; x < 5; x++) {
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
        if ($scope.characters.indexOf($scope.character) != -1) {
            alert('Character is already in the list!');
            return;
        }

        $scope.characters.push($scope.character);
        $scope.wrong();
        $scope.character = "";

        /*var result = HangmanService.verifyCharacter($scope.char);

        if (result == false) {
            return $scope.wrong();
        }

        return $scope.correct();*/
    };

    $scope.correct = function () {
        alert('Correct');
    };

    $scope.wrong = function() {
        $scope.attempt++;
        $scope.wrongChars += $scope.character + " ";
    };
}]);