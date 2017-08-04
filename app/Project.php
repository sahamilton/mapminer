<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $table="projects";

    public $fillable=[ 
           'dodge_repnum',
           'project_title',
           'project_addr1',
           'project_addr2',
           'project_city',
           'project_state',
           'project_zipcode',
           'project_county_name',
           'project_county_code',
           'structure_header',
           'project_type',
           'stage',
           'ownership',
           'bid_date',
           'start_year',
           'start_yearmo',
           'target_start_date',
           'target_comp_date',
           'work_type',
           'status',
           'project_value',
           'total_project_value',
           'value_range',
           
           ];

   public function contacts(){

    return $this->belongsToMany(ProjectContact::class,'project_company_contact','contact_id','project_id')->withPivot('type','company_id')
   }

    public function companies(){
    	return $this->belongsToMany(ProjectCompany::class,'project_company_contact','project_id','projectcompany_id')->withPivot('type','contact_id');
    }


    public function _import_csv($filename, $table,$fields)
	{
	$filename = str_replace("\\","/",$filename);

	$query = sprintf("LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ". $table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.");", $filename);
	
	
	try {
		return  \DB::connection()->getpdo()->exec($query);
	}
	catch (Exception $e)
		{
		 throw new Exception( 'Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
		
		}
	
	}


  public function findNearbyProjects($lat,$lng,$distance,$limit)
  
  {
    
    $params = array(":loclat"=>$lat,":loclng"=>$lng,":distance"=>$distance);
    
    // Get the users serviceline associations
    // 
          
    $query = "SELECT id,project_title,street,city,state,zip,project_lat,project_lng, distance_in_mi
        FROM (
          SELECT projects.id as id, 
            project_title,
            project_addr1 as street,
            project_city as city,
            project_state as state,
            project_zipcode as zip,
            project_lat,
            project_lng,
            r,
                 69.0 * DEGREES(ACOS(COS(RADIANS(latpoint))
                     * COS(RADIANS(project_lat))
                     * COS(RADIANS(longpoint) - RADIANS(project_lng))
                     + SIN(RADIANS(latpoint))
                     * SIN(RADIANS(project_lat)))) AS distance_in_mi
           FROM 
            projects
           JOIN (
              SELECT  ".$lat."  AS latpoint,  ".$lng." AS longpoint, ".$distance." AS r
             ) AS p
           WHERE 
            project_lat
                BETWEEN latpoint  - (r / 69)
                AND latpoint  + (r / 69)
               AND project_lng
                BETWEEN longpoint - (r / (69 * COS(RADIANS(latpoint))))
                AND longpoint + (r / (69 * COS(RADIANS(latpoint))))
             ) d
       
       WHERE distance_in_mi <= r ";
       
       $query.="  ORDER BY distance_in_mi";
//dd(str_replace("\n","",$query));
       $result = \DB::select($query);

    return $result;
  }
}
