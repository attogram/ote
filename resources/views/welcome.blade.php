<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Open Translation Engine</title>
<style>
body {
    background-color: #fff;
    color: #000;
    font-family: sans-serif;
    margin: 20px;
}
</style>
</head>
<body>
<h1>Open Translation Engine</h1>
<h2>Development Branch v2-laravel</h2>
<h3>v2.0.0-alpha.11</h3>

<p>System:</p>
<ul>
@auth
    <li><a href="{{ url('/home') }}">Home</a></li>
@else
    <li><a href="{{ route('login') }}">Login</a></li>
    @if (Route::has('register'))
    <li><a href="{{ route('register') }}">Register</a></li>
    @endif
@endauth
</ul>

<p>Info:</p>
<ul>
    <li><a href="https://github.com/attogram/ote">https://github.com/attogram/ote</a></li>
    <li><a href="https://attogram.github.io/ote/">https://attogram.github.io/ote</a></li>
</ul>

<p>Debug:</p>
<pre>
locale: <?= app()->getLocale(); ?> 
</pre>
</body>
</html>
