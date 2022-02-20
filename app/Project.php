<?php

namespace App;

use App\Presenters\LocationPresenter;

class Project extends Model
{
    use Geocode, Addressable;
    public $table = 'projects';
    public $incrementing = false;
    public $statuses = ['Claimed', 'Closed'];
    public $fillable = [
           'source_ref',
           'project_title',
           'street',
           'addr2',
           'city',
           'state',
           'zip',
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
           'lat',
           'lng',
           'position',

           ];
    public $getStatusOptions = [
        1=>'Project data is completely inaccurate. No project or project completed.',
        2=>'Project data is incomplete and / or not useful.',
        3=>'Project data is accurate but there is no sales / service opportunity.',
        4=>'Project data is accurate and there is a possibility of sales / service.',
        5=>'Project data is accurate and there is a definite opportunity for sales / service',
      ];

    public function contacts()
    {
        return $this->belongsToMany(ProjectContact::class, 'project_company_contact', 'contact_id', 'project_id')->withPivot('type', 'company_id');
    }

    public function companies()
    {
        return $this->belongsToMany(ProjectCompany::class, 'project_company_contact', 'project_id', 'company_id')->withPivot('type', 'contact_id');
    }

    public function owner()
    {
        return $this->belongsToMany(Person::class, 'person_project', 'related_id')->withPivot('status', 'ranking');
    }

    public function source()
    {
        return $this->belongsTo(ProjectSource::class, 'project_source_id');
    }

    public function owned()
    {
        return $this->belongsToMany(Person::class, 'person_project', 'related_id')
            ->withPivot('status', 'ranking', 'type')
            ->wherePivot('type', '=', 'project')
            ->where('person_id', '=', auth()->user()->person->id)
            ->first();
    }

    public function ownersProjects($id)
    {
        return $this->belongsToMany(Person::class, 'person_project', 'related_id')
            ->withPivot('status')
            ->where('person_id', '=', $id)
            ->get();
    }

    public function relatedNotes()
    {
        return $this->hasMany(Note::class, 'related_id')->where('type', '=', 'project')->with('writtenBy');
    }

    public function projectcount()
    {
        return \DB::select('select count(`id`) as total from projects');
    }

    public function projectStats($id = null)
    {
        if ($id) {
            $query = "select source,firstname, lastname, persons.id as id ,person_project.status as pstatus, count(person_project.status) as count,avg(ranking) as rating 
        from `persons` ,`person_project`, `projects`, `projectsource` 
        where `persons`.`id` = `person_project`.`person_id` 
        and `person_project`.`related_id` = `projects`.`id` 
        and `person_project`.`type`='project'
        and `projects`.`project_source_id` = `projectsource`.`id` 
        and `projectsource`.`id` = ".$id.'
        group by `person_id`,`pstatus`';
        } else {
            $query = 'select firstname, lastname, persons.id as id ,person_project.status as pstatus, count(person_project.status) as count,avg(ranking) as rating 
         from `persons` ,`person_project`
         where `persons`.`id` = `person_project`.`person_id` 
         group by `person_id`,`pstatus`';
        }

        return \DB::select($query);
    }

    public function _import_csv($filename, $table, $fields)
    {
        $filename = str_replace('\\', '/', $filename);

        $query = sprintf("LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ".$table." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'  IGNORE 1 LINES (".$fields.');', $filename);

        try {
            return  \DB::connection()->getpdo()->exec($query);
        } catch (Exception $e) {
            throw new Exception('Something really has gone wrong with the import:\r\n<br />'.$query, 0, $e);
        }
    }
}
