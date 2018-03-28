<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class GitVersion extends Model
{

    protected $table='gittracking';
    protected $dates =['commitdate'];
    protected $fillable = ['hash','author','message','commitdate'];

    const MAJOR = 2;
    const MINOR = 5;
    const PATCH = 17;


    public static function get()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $version = trim(exec('git tag'));
        $branch = str_replace("(HEAD -> ","" ,exec('git log -n1 -s --pretty=%d HEAD')). " branch";
        $branch = str_replace(")","",$branch);
        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('America/Los_Angeles'));
        return sprintf('%s (%s),commited %s', $branch, $commitHash, $commitDate->format('M jS Y @ g:m a'));        
    }
    
    public function history(){
        $lastCommit = $this->max('commitdate');
        $command = 'git log --after="'.$lastCommit.'"';
        $dir = app_path();
        $output = array();
        chdir($dir);
        exec($command,$output);
        $history = array();
        foreach($output as $line){
            if(strpos($line, 'commit')===0){
                if(!empty($commit)){
                    array_push($history, $commit);  
                    unset($commit);
                }
                $commit['hash']   = substr($line, strlen('commit'));
            }
            else if(strpos($line, 'Author')===0){
                $commit['author'] = substr($line, strlen('Author:'));
            }
            else if(strpos($line, 'Date')===0){
                $commit['commitdate']   = substr($line, strlen('Date:'));
            }
            if(isset($commit['message'])){
                $commit['message'] .= $line;
            }
            else{
                $commit['message'] = $line;
            }
        }
        if(!empty($commit)) {
            array_push($history, $commit);
        }
      $this->insert($history);
    }
    

    public function insert($history){
 
        foreach ($history as $commit){
            if(! $this->where('hash','=',$commit['hash'])->first()){
                $commit['commitdate'] = Carbon::parse($commit['commitdate']);
                $commit['message'] = preg_replace("#(\A\N* -0[7,8]00 )#", "", $commit['message']);
                $commit['author'] = preg_replace("#( <\N*>)#", "", $commit['author']);
                $this->create($commit);
            }
        
        }

    }
}
