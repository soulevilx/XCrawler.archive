<?php

return [
    // IMPORTANT NOTE: The process of looking inside directories (and subdirectories)
    // and determining what kind of files they are is not light from a computational
    // perspective. It takes time. So don't go too crazy with the folders you
    // tell Devtools to look into. Try to limit it to directories where you
    // know those types of files will be stored.

    'paths' => [

        // Where should DevTools look for Eloquent models?
        'models' => [
            // if string, DevTools will look into that directory and all subdirectories
            // if array, the second item (true/false) can tell DevTools not to look into subdirectories
            [app_path(), false], // files in the app directory (but no subdirectories)
            app_path('Models'),
        ],

        // Where should DevTools look for CrudControllers?
        'crud_controllers' => [
            app_path('Http/Controllers'),
        ],

        // Where should DevTools look for FormRequests?
        'crud_requests' => [
            app_path('Http/Requests'),
        ],

        // Where should DevTools look for migrations?
        'migrations' => [
            base_path('database/migrations'),
        ],

        // Where should DevTools look for factories?
        'factories' => [
            base_path('database/factories'),
        ],

        // Where should DevTools look for seeders?
        'seeders' => [
            base_path('database/seeders'),
        ],

    ],

    // ** EXPERIMENTAL FEATURE **
    // Which code editor would you like to open when you click on file links?
    //
    // Options: vscode, vscode-insiders, subl, sublime, textmate, emacs,
    // macvim, phpstorm, idea, atom, nova, netbeans, xdebug
    //
    // Note: a file link will be constructed (eg: vscode://open?url=path-to-file), but
    // it is up to your system to intepret that file link and actually open the file.
    // VSCode does that by default, most other editors do not. To add support for
    // file links for your particular Operating System & Editor, search for
    // something like "URL Handler for [Sublime Text] on [Mac OS X]".
    'editor' => env('DEVTOOLS_EDITOR') ?? env('IGNITION_EDITOR') ?? false,

];
