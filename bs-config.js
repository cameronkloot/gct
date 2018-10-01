// browser-sync start --config bs-config.js
module.exports = {
    "files": [
        'style.css',
        'lib/*.php',
        'templates/*.php',
        '*.php',
        'assets/js/*.js',
        'assets/css/*.css'
    ],
    "watchEvents": [
        "change"
    ],
    "watch": true,
    "socket": {
        "domain": "localhost:3000"
    }
};
