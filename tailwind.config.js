const preset = require('./vendor/filament/filament/tailwind.config.preset')

module.exports = {
    presets: [preset],
    content: [
        './src/Tables/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
