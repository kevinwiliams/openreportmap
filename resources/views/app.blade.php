<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OpenReportMap</title>
    <!-- Material Web: loaded by the React app (bundled by Vite) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
</head>
<body>
    <div id="root"></div>
    <script>
        // Provide no-op React Refresh helpers to avoid a runtime preamble detection
        // error in certain dev environments where the plugin preamble may not run
        // early enough. These are harmless no-ops when React Refresh is not active.
        if (!window.$RefreshReg$) {
            window.$RefreshReg$ = function() {};
        }
        if (!window.$RefreshSig$) {
            window.$RefreshSig$ = function() { return function() {}; };
        }
    </script>
    @vite(['resources/js/main.jsx', 'resources/css/app.css'])
</body>
</html>
