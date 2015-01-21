<!DOCTYPE html>
<html lang="en">
    <head>
        <title>URL Shortener</title>
        <link rel="stylesheet" href="/assets/css/styles.css">
    </head>
    <body>
        <div id="container">
            <h2>Uber-Shortener</h2>
            @if (Session::has('errors'))
                <h3 class="error">{{ $errors->first('link') }}</h3>
            @endif

            @if (Session::has('link'))
                <h3 class="success">
                    {{ HTML::link(Session::get('link'), 'Click here for your shortened URL') }}
                </h3>
            @endif

            @if (Session::has('message'))
                <h3 class="error">{{ Session::get('message') }}</h3>
            @endif
            {{ Form::open(['url' => '/', 'method' => 'post']) }}

            {{ Form::text( 'link', Input::old('link'),
                ['placeholder' => 'Insert your URL here and press enter!']) }}

            {{ Form::close() }}
        </div>
    </body>
</html>