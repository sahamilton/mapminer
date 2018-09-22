<?php
namespace App;
class State extends Model {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];
	private $states=[
		''=>"Select a State",
		'AL'=>"Alabama",  
		'AK'=>"Alaska",  
		'AZ'=>"Arizona",  
		'AR'=>"Arkansas",  
		'CA'=>"California",  
		'CO'=>"Colorado",  
		'CT'=>"Connecticut",  
		'DE'=>"Delaware",  
		'DC'=>"District Of Columbia",  
		'FL'=>"Florida",  
		'GA'=>"Georgia",  
		'HI'=>"Hawaii",  
		'ID'=>"Idaho",  
		'IL'=>"Illinois",  
		'IN'=>"Indiana",  
		'IA'=>"Iowa",  
		'KS'=>"Kansas",  
		'KY'=>"Kentucky",  
		'LA'=>"Louisiana",  
		'ME'=>"Maine",  
		'MD'=>"Maryland",  
		'MA'=>"Massachusetts",  
		'MI'=>"Michigan",  
		'MN'=>"Minnesota",  
		'MS'=>"Mississippi",  
		'MO'=>"Missouri",  
		'MT'=>"Montana",
		'NE'=>"Nebraska",
		'NV'=>"Nevada",
		'NH'=>"New Hampshire",
		'NJ'=>"New Jersey",
		'NM'=>"New Mexico",
		'NY'=>"New York",
		'NC'=>"North Carolina",
		'ND'=>"North Dakota",
		'OH'=>"Ohio",  
		'OK'=>"Oklahoma",  
		'OR'=>"Oregon",  
		'PA'=>"Pennsylvania",  
		'RI'=>"Rhode Island",  
		'SC'=>"South Carolina",  
		'SD'=>"South Dakota",
		'TN'=>"Tennessee",  
		'TX'=>"Texas",  
		'UT'=>"Utah", 
		'VI'=>"Virgin Islands", 
		'VT'=>"Vermont",  
		'VA'=>"Virginia",  
		'WA'=>"Washington",  
		'WV'=>"West Virginia",  
		'WI'=>"Wisconsin",  
		'WY'=>"Wyoming",
		"AB"=>"Alberta",
		"BC"=>"British Columbia",
		"MB"=>"Manitoba",
		"NB"=>"New Brunswick",
		"NL"=>"Newfoundland and Labrador",
		"NS"=>"Nova Scotia",
		"ON"=>"Ontario",
		"PE"=>"Prince Edward Island",
		"QC"=>"Quebec",
		"SK"=>"Saskatchewan",
		"NT"=>"Northwest Territories",
		"NU"=>"Nunavut",
		"YT"=>"Yukon",];

	// Don't forget to fill this array
	protected $fillable = [];
	
	public function branches() 
	{
		return $this->hasMany(Branch::class,'statecode','state');	
	}
	
	public function locations() 
	{
		return $this->hasMany(Location::class,'statecode','state');	
	}

	public function getStates()
	{
		return $this->states;
	}
}