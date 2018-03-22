<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GitVersion extends Model
{

    const MAJOR = 3;
    const MINOR = 0;
    const PATCH = 2;

    public static function get()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $branch = str_replace("(HEAD -> ","",exec('git log -n1 -s --pretty=%d HEAD'));
        $branch = str_replace(")","",$branch) ." branch";

        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('America/Los_Angeles'));

        return sprintf('v%s.%s.%s.%s (%s) committed %s', self::MAJOR, self::MINOR, self::PATCH,$branch, $commitHash, $commitDate->format('M jS Y g:m a'));
    }

}
