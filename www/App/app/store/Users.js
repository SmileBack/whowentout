Ext.define('App.store.Users', {
    extend: 'Ext.data.Store',

    config: {
        model: 'App.model.User',
        data: [
            {
                "id": 1,
                "firstName": "George",
                "lastName": "Washington",
                "facebookId": "100",
                "age": 24,
                "networks": [
                    {"id": 100, "name": 'NYU', "type": 'college'},
                    {"id": 101, "name": 'Georgetown', "type": 'college'},
                    {"id": 205, "name": "Stanford", type: "college"}
                ]
            },
            { "id": 2, "firstName": "John", "lastName": "Adams", "facebookId": "101", "age": 35},
            { "id": 3, "firstName": "Thomas", "lastName": "Jefferson", "facebookId": "102", "age": 21},
            { "id": 4, "firstName": "James", "lastName": "Madison", "facebookId": "103", "age": 22},
            { "id": 5, "firstName": "James", "lastName": "Monroe", "facebookId": "104", "age": 21},
            { "id": 6, "firstName": "John", "lastName": "Quincy Adams", "facebookId": "105", "age": 25},
            { "id": 7, "firstName": "Andrew", "lastName": "Jackson", "facebookId": "106", "age": 33},
            { "id": 8, "firstName": "Martin", "lastName": "Van Buren", "facebookId": "107"},
            { "id": 9, "firstName": "William", "lastName": "Henry Harrison", "facebookId": "108"},
            { "id": 10, "firstName": "John", "lastName": "Tyler", "facebookId": "109"},
            { "id": 11, "firstName": "James", "middleInitial": "K", "lastName": "Polk", "facebookId": "110"},
            { "id": 12, "firstName": "Zachary", "lastName": "Taylor", "facebookId": "111"},
            { "id": 13, "firstName": "Millard", "lastName": "Fillmore", "facebookId": "112"},
            { "id": 14, "firstName": "Franklin", "lastName": "Pierce", "facebookId": "113"},
            { "id": 15, "firstName": "James", "lastName": "Buchanan", "facebookId": "114"},
            { "id": 16, "firstName": "Abraham", "lastName": "Lincoln", "facebookId": "115"},
            { "id": 17, "firstName": "Andrew", "lastName": "Johnson", "facebookId": "116"},
            { "id": 18, "firstName": "Ulysses", "middleInitial": "S", "lastName": "Grant", "facebookId": "117"},
            { "id": 19, "firstName": "Rutherford", "middleInitial": "B", "lastName": "Hayes", "facebookId": "118"},
            { "id": 20, "firstName": "James", "middleInitial": "A", "lastName": "Garfield", "facebookId": "119"},
            { "id": 21, "firstName": "Chester", "lastName": "Arthur", "facebookId": "120"},
            { "id": 22, "firstName": "Grover", "lastName": "Cleveland", "facebookId": "121"},
            { "id": 23, "firstName": "Benjamin", "lastName": "Harrison", "facebookId": "122"},
            { "id": 24, "firstName": "William", "lastName": "McKinley", "facebookId": "123"},
            { "id": 25, "firstName": "Theodore", "lastName": "Roosevelt", "facebookId": "124"},
            { "id": 26, "firstName": "William", "lastName": "Howard Taft", "facebookId": "125"},
            { "id": 27, "firstName": "Woodrow", "lastName": "Wilson", "facebookId": "126"},
            { "id": 28, "firstName": "Warren", "middleInitial": "G", "lastName": "Harding", "facebookId": "127"},
            { "id": 29, "firstName": "Calvin", "lastName": "Coolidge", "facebookId": "128"},
            { "id": 30, "firstName": "Herbert", "lastName": "Hoover", "facebookId": "129"},
            { "id": 31, "firstName": "Franklin", "middleInitial": "D", "lastName": "Roosevelt", "facebookId": "130"},
            { "id": 32, "firstName": "Harry", "middleInitial": "S", "lastName": "Truman", "facebookId": "131"},
            { "id": 33, "firstName": "Dwight", "middleInitial": "D", "lastName": "Eisenhower", "facebookId": "132"},
            { "id": 34, "firstName": "John", "middleInitial": "F", "lastName": "Kennedy", "facebookId": "133"},
            { "id": 35, "firstName": "Lyndon", "middleInitial": "B", "lastName": "Johnson", "facebookId": "134"},
            { "id": 36, "firstName": "Richard", "lastName": "Nixon", "facebookId": "135"},
            { "id": 37, "firstName": "Gerald", "lastName": "Ford", "facebookId": "136"},
            { "id": 38, "firstName": "Jimmy", "lastName": "Carter", "facebookId": "137"},
            { "id": 39, "firstName": "Ronald", "lastName": "Reagan", "facebookId": "138"},
            { "id": 40, "firstName": "George", "lastName": "Bush", "facebookId": "139"},
            { "id": 41, "firstName": "Bill", "lastName": "Clinton", "facebookId": "140"},
            { "id": 42, "firstName": "George", "middleInitial": "W", "lastName": "Bush", "facebookId": "141"},
            { "id": 43, "firstName": "Barack", "lastName": "Obama", "facebookId": "142" }
        ]
    }
});
