<!doctype html>
<html lang="es" ng-app="SARA" ngs-strict-di>

	<head>
		<meta charset="UTF-8">
		<title><% env('APP_NAME', 'SARA') %></title>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimal-ui">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">


		<link rel="shortcut icon" href="/img/favicon.ico">

		<link   rel="stylesheet"              href="/css/libs.min.css?202003260918" />
		<link   rel="stylesheet"              href="<%  elixir('css/all.min.css') %>" />

		<script defer type="application/javascript" src="/js/libs.min.js?202003260918"></script>
		<script defer type="application/javascript" src="<%  elixir('js/app.min.js') %>"></script>
	</head>

	<body layout>
		<div id='Main' ui-view flex layout></div>
	</body>

</html>