## About Mapminer

Mapminer is a proprietary web application developed for Staffing Companies. It is based on the Laravel framework.
The application's focus is on supporting the branch selling activities.
The application is geocentric in that all locations, branches and people have an associated latitude and longitude that facilitates 'nearest' searches.

## Prerequisites
<ul>
<li>PHP 8.0</li>
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
