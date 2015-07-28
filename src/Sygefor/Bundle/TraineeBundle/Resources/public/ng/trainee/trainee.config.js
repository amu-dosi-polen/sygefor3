/**
 * TraineeBundle
 */
sygeforApp.config(["$listStateProvider", "$dialogProvider", "$widgetProvider", function($listStateProvider, $dialogProvider, $widgetProvider) {

    // trainee states
    $listStateProvider.state('trainee', {
        url: "/trainee?q",
        abstract: true,
        templateUrl: "listbundle/list.html",
        controller:"TraineeListController",
        breadcrumb: [
            { label: "Publics", sref: "trainee.table" }
        ],
        resolve: {
            search: function ($searchFactory, $stateParams, $user) {
                var search = $searchFactory('trainee.search');
                search.query.sorts = {'lastName.source': 'asc'};
                search.query.filters['organization.name.source'] = $user.organization.name;
                search.extendQueryFromJson($stateParams.q);
                return search.search().then(function() { return search; });
            }
        },
        states: {
            table: {
                url: "",
                icon: "fa-bars",
                label: "Tableau",
                weight: 0,
                controller: 'ListTableController',
                templateUrl: "traineebundle/trainee/states/table/table.html"
            },
            detail: {
                url: "/detail",
                icon: "fa-eye",
                label: "Liste détaillée",
                weight: 1,
                templateUrl: "listbundle/states/detail/detail.html",
                controller: 'ListDetailController',
                data:{
                    resultTemplateUrl: "traineebundle/trainee/states/detail/result.html"
                },
                states: {
                    view: {
                        url: "/:id",
                        templateUrl: "traineebundle/trainee/states/detail/trainee.html",
                        controller: 'TraineeDetailViewController',
                        resolve: {
                            data: function($http, $stateParams) {
                                var url = Routing.generate('trainee.view', {id: $stateParams.id});
                                return $http({method: 'GET', url: url}).then (function (data) { return data.data; });
                            }
                        },
                        breadcrumb: {
                            label: "{{ data.trainee.fullName }}"
                        }
                    }
                }
            }
        }
    });

    /**
     * DIALOGS
     */
    $dialogProvider.dialog('trainee.create', /* @ngInject */ {
        templateUrl: 'traineebundle/trainee/dialogs/create.html',
        controller: function($scope, $modalInstance, $dialogParams, $state, $http, form, growl) {
            $scope.dialog = $modalInstance;
            $scope.dialog.params = $dialogParams;
            $scope.form = form;
            $scope.onSuccess = function(data) {
                growl.addSuccessMessage("Le stagiaire a bien été créé.");
                $scope.dialog.close(data);
            };
        },
        resolve:{
            // @todo blaise : fix form directive to remove this resolve
            form: function ($http){
                return $http.get(Routing.generate('trainee.create')).then(function (response) {
                    return response.data.form;
                });
            }
        }
    });

    /**
     * trainee deletion modal window
     */
    $dialogProvider.dialog('trainee.delete', /* @ngInject */ {
        templateUrl: 'traineebundle/trainee/dialogs/delete.html',
        controller: function($scope, $modalInstance, $dialogParams, $state, $http, growl) {
            $scope.dialog = $modalInstance;
            $scope.dialog.params = $dialogParams;
            $scope.ok = function() {
                var url = Routing.generate('trainee.delete', {id: $dialogParams.trainee.id});
                $http.post(url).then(function (response){
                    growl.addSuccessMessage("Le stagiaire a bien été supprimé.");
                    $scope.dialog.close(response.data);
                });
            };
        }

    });

    /**
     * trainee merge modal window
     */
    $dialogProvider.dialog("trainee.merge", /* @ngInject */ {
        controller: 'TraineeMerge',
        templateUrl: 'traineebundle/batch/traineeMerge/traineeMerge.html',
        size: 'lg',
        resolve: {
            config: function ($http, $dialogParams) {
                var url = Routing.generate('sygefor_list.batch_operation.modal_config', {service: 'sygefor_list.batch.trainee_merge'});
                return $http.get(url).then(function (response) {
                    return response.data;
                });
            },
            trainees: function($dialogParams, $entityManager, $q) {
                var trainees = [];
                angular.forEach ($dialogParams.items, function(item){
                    this.push ($entityManager('SygeforTraineeBundle:Trainee').find(item));
                }, trainees);
                return $q.all(trainees).then(function(results){return results;});
            }
        }
    });

    /**
     * trainee change password modal window
     */
    $dialogProvider.dialog('trainee.changePwd', /* @ngInject */ {
        templateUrl: 'traineebundle/trainee/dialogs/change-password.html',
        controller: function($scope, $modalInstance, $dialogParams, $state, $http, form, growl) {
            $scope.dialog = $modalInstance;
            $scope.dialog.params = $dialogParams;
            $scope.form = form;
            $scope.onSuccess = function(response) {
                growl.addSuccessMessage("Le mot de passe a bien été changé.");
                $scope.dialog.close(response);
            };
        },
        resolve: {
            form: function ($http, $dialogParams){
                return $http.get(Routing.generate('trainee.changepwd', {id: $dialogParams.trainee.id })).then(function(response) {
                    return response.data.form;
                });
            }
        }

    });

    /**
     * trainee change organization modal window
     */
    $dialogProvider.dialog('trainee.changeOrg', /* @ngInject */ {
        templateUrl: 'traineebundle/trainee/dialogs/change-organization.html',
        controller: function($scope, $modalInstance, $dialogParams, $state, $http, form, growl) {
            $scope.dialog = $modalInstance;
            $scope.dialog.params = $dialogParams;
            $scope.form = form;
            $scope.onSuccess = function(response) {
                growl.addSuccessMessage("Le stagiaire a bien changé d'URFIST de référence.");
                $scope.dialog.close(response);
            };
        },
        resolve: {
            form: function ($http, $dialogParams){
                return $http.get(Routing.generate('trainee.changeorg', {id: $dialogParams.trainee.id })).then(function(response) {
                    return response.data.form;
                });
            }
        }

    });

    /**
     * ignore a trainee duplicate
     */
    $dialogProvider.dialog('trainee.ignoreDuplicate', /* @ngInject */ {
        templateUrl: 'traineebundle/trainee/dialogs/ignore-duplicate.html',
        controller: function($scope, $modalInstance, $dialogParams, $state, $http, form, growl) {
            $scope.dialog = $modalInstance;
            $scope.dialog.params = $dialogParams;
            $scope.form = form;
            $scope.onSuccess = function(response) {
                growl.addSuccessMessage("Le statut du doublon a bien été mis à jour.");
                $scope.dialog.close(response);
            };
        },
        resolve: {
            form: function ($http, $dialogParams){
                return $http.get(Routing.generate('trainee.changeduplicateignorance', {id: $dialogParams.duplicate.id })).then(function(response) {
                    return response.data.form;
                });
            }
        }
    });

    /**
     * WIDGETS
     */
    $widgetProvider.widget("trainee", /* @ngInject */ {
        controller: 'WidgetListController',
        templateUrl: 'traineebundle/trainee/widget/trainee.html',
        options: function($user) {
            return {
                route: 'trainee.search',
                rights: ['sygefor_trainee.rights.trainee.own.view', 'sygefor_trainee.rights.trainee.all.view'],
                state: 'trainee.table',
                title: 'Derniers stagiaires inscrits',
                size: 10,
                filters:{
                    'organization.name.source': $user.organization.name
                },
                sorts: {'createdAt': 'desc'}
            }
        }
    });

    $widgetProvider.widget("trainee.duplicate", /* @ngInject */ {
        controller: 'WidgetListController',
        templateUrl: 'traineebundle/trainee/widget/trainee.duplicate.html',
        options: function($user) {
            return {
                route: 'trainee.search',
                rights: ['sygefor_trainee.rights.trainee.own.view', 'sygefor_trainee.rights.trainee.all.view'],
                state: 'trainee.table',
                title: 'Doublons détectés',
                size: 10,
                filters:{
                    'organization.name.source': $user.organization.name,
                    "data.duplicate": {
                        "type": "exists",
                        "args" : ["duplicatesList"]
                    }
                },
                sorts: {'createdAt': 'desc'},
                open: false
            }
        }
    });

}]);