module.exports = {
    "setupFiles": [
        "./jest.setup.js"
    ],
    "reporters": [
        "default",
        ["jest-junit", {
            outputDirectory: "./reports/js"
        }]
    ],
    "coverageDirectory": "./reports/js",
    "coverageReporters": [
        [
            "lcov",
            {
                "projectRoot": "../"
            }
        ]
    ],
    "globals": {
        "vue-jest": {
            "tsConfig": "./tsconfig.json"
        }
    },
    "moduleFileExtensions": [
        "js",
        "ts",
        "json",
    ],
    "moduleNameMapper": {
        "\\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$": "<rootDir>/assets/fileMock.js",
        "\\.(css|less)$": "<rootDir>/assets/styleMock.js"
    },
    "testResultsProcessor": "jest-sonar-reporter",
    "transform": {
        "^.+\\.js$": "babel-jest",
        "^.+\\.tsx?$": "ts-jest",
        "^.+\\.vue$": "vue-jest"
    }
}
