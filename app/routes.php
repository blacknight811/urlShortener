<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('form');
});

Route::post('/', function()
{
	//Define the Form validation rule (s)
	$rules = [
		'link' => 'required|url'
	];

	//Run form validation
	$validation = Validator::make(Input::all(), $rules);

	//If validation fails, return to main page with errors
	if ($validation->fails()){
		return Redirect::to('/')
			->withInput()
			->withErrors($validation);
	} else {
		//Check if link exists in DB. If true get first()
		$link = Link::where('url', '=', Input::get('link'))
			->first();

		//If already in DB, pass to view
		if($link){
			return Redirect::to('/')
				->withInput()
				->with('link', $link->hash);
			//Else create new unique URL
		} else {
			//Create new unique Hash
			do {
				$newHash = Str::random(6);
			} while(Link::where('hash', '=', $newHash)
				->count() > 0);

			//Create new DB record
			Link::create([
				'url' => Input::get('link'),
				'hash' => $newHash
			]);

			//Return shortened URL to action
			return Redirect::to('/')
				->withInput()
				->withLink($newHash);
		}

	}
});

Route::get('{hash}', function($hash){
	//Check if hash is from a URL from DB
	$link = Link::where('hash', '=', $hash)
		->first();

	//If found, redirect to URL
	if($link) {
		return Redirect::to($link->url);
		//If not found, redirect to home with errors
	} else {
		return Redirect::to('/')
			->withMessage('Invalid Link');
	}
})->where('hash','[0-9a-zA-Z]{6}');