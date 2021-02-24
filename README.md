## About TBMapminer

TBMapminer is a proprietary web application developed for TrueBlue. It is based on the Laravel framework.
The application was intially designed to provide information on nearby National Account locations.
The application is geocentric in that all locations, branches and people have an associated latitude and longitude that facilitates 'nearest' searches.

## Prerequisites
<ul>
<li>PHP 7.4</li>
<li>Laravel 8.26</li>
<li>After cloning this repository, go to the root folder, run the following command/s,
<pre>
    composer install
    composer update
    npm install
    npm run dev
</pre>
</li>
<li>Rename .env.example to .env and provide your database details there.</li>
<li>Restore the mapminer database</li>
<li>Generate the application key<pre>
	php artisan key:generate
</pre>
</li>
</ul>
