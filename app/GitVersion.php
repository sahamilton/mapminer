<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GitVersion extends Model
{

    const MAJOR = 3;
    const MINOR = 2;
    const PATCH = 3;

    public static function get()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $branch = exec('git log -n1 -s --pretty=%d HEAD');

        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('America/Los_Angeles'));

        return sprintf('v%s.%s.%s.%s (%s)', self::MAJOR, self::MINOR, self::PATCH,$branch, $commitHash, $commitDate->format('Y-m-d H:m:s'));
    }

}
