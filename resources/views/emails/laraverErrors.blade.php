<!DOCTYPE html>
<html>
<head>
    <title>Exception Occurred</title>
</head>
<body>
<h2>An Exception Occurred</h2>
<p><strong>Message:</strong> {{ $exception->getMessage() }}</p>
<p><strong>File:</strong> {{ $exception->getFile() }}</p>
<p><strong>Line:</strong> {{ $exception->getLine() }}</p>
<p><strong>Stack Trace:</strong></p>
<pre>{{ $exception->getTraceAsString() }}</pre>
</body>
</html>
