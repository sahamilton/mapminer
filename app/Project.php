<?php

namespace App;


class Project extends Model
{
    public $table="projects";
    public $statuses = ['Claimed','Closed'];
    public $fillable=[ 
           'source_ref',
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
           'pr_status',
           
           ];
     public $getStatusOptions =  [
        1=>'Project data is completely inaccurate. No project or project completed.',
        2=>'Project data is incomplete and / or not useful.',
        3=>'Project data is accurate but there is no sales / service opportunity.',
        4=>'Project data is accurate and there is a possibility of sales / service.',
        5=>'Project data is accurate and there is a definite opportunity for sales / service'
      ];
   public function contacts(){

    return $this->belongsToMany(ProjectContact::class,'project_company_contact','contact_id','project_id')->withPivot('type','company_id');
   }

    public function companies(){
    	return $this->belongsToMany(ProjectCompany::class,'project_company_contact','project_id','company_id')->withPivot('type','contact_id');
    }
    public function fullAddress(){
      return $this->project_addr1 . "," . $this->project_city. " " . $this->project_state . " " . $this->project_zipcode;

    }
    public function owner(){
      return $this->belongsToMany(Person::class)->withPivot('status','ranking');
    }
   
    public function source(){
      return $this->belongsTo(ProjectSource::class,'project_source_id');
    }

    public function owned(){
      return $this->belongsToMany(Person::class)
      ->withPivot('status','ranking')
      ->where('person_id','=',auth()->user()->person()->first()->id)->first();
    }

    public function ownersProjects($id){

       return $this->belongsToMany(Person::class)->withPivot('status')
      ->where('person_id','=',$id)->get();

    }
    public function relatedNotes() {

      return $this->hasMany(Note::class,'related_id')->with('writtenBy');

    }
    
  
    public function projectcount(){
      return \DB::select('select count(`id`) as total from projects');
    }

    public function projectStats($id=null){
     
      if($id){
        $query="select source,firstname, lastname, persons.id as id ,person_project.status as pstatus, count(person_project.status) as count,avg(ranking) as rating 
        from `persons` ,`person_project`, `projects`, `projectsource` 
        where `persons`.`id` = `person_project`.`person_id` 
        and `person_project`.`project_id` = `projects`.`id` 
        and `projects`.`project_source_id` = `projectsource`.`id` 
        and `projectsource`.`id` = 1 
        group by `person_id`,`pstatus`";
      }else{
         $query="select firstname, lastname, persons.id as id ,person_project.status as pstatus, count(person_project.status) as count,avg(ranking) as rating 
         from `persons` ,`person_project`
         where `persons`.`id` = `person_project`.`person_id` 
         group by `person_id`,`pstatus`";

      }
      return \DB::select($query);

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


  public function findNearbyProjects($lat,$lng,$distance,$limit,$servicelines)
  
  {
   
    $params = array(":loclat"=>$lat,":loclng"=>$lng,":distance"=>$distance);
    
    // Get the users serviceline associations
    // 
          
    $query = "SELECT *
        FROM (
          SELECT projects.id as id, 
            project_title,
            project_addr1 as street,
            project_city as city,
            project_state as state,
            project_zipcode as zip,
            project_lat,
            serviceline_id,
            project_lng,
            project_type,
            ownership,
            total_project_value,
            stage,
            person_project.status as prstatus,
            structure_header,
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
          
          left join person_project on 
          projects.id = person_project.project_id
          
           WHERE 
           pr_status is null 
           and project_lat
                BETWEEN latpoint  - (r / 69)
                AND latpoint  + (r / 69)
               AND project_lng
                BETWEEN longpoint - (r / (69 * COS(RADIANS(latpoint))))
                AND longpoint + (r / (69 * COS(RADIANS(latpoint))))
             ) d
       
       WHERE distance_in_mi <= r 
       AND serviceline_id in ('".implode("','",$servicelines)."')";
       
       $query.="  ORDER BY distance_in_mi";
//dd(str_replace("\n","",$query));
       $result = \DB::select($query);

    return $result;
  }
}
