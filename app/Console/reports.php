<?php
$report_schedules = ReportSchedule::all();
foreach ($report_schedules as $report_schedule) {

    $method_name = (string)$report_schedule->report->method_name;
    $schedule->call(
        function () use ($method_name, $report_schedule) {
            $emailSchedules = new EmailSchedules();
            $email_List=array_unique(
                array_merge(
                    $report_schedule->users()->get()->lists("email")->toArray(), $report_schedule->groups()->with(
                        ["users"=>function ($query) { 
                            $query->select("email");
                        }
                        ]
                    )->get()->toArray()
                )
            );
            $users=\App\User::whereIn("email", $email_List)->get(["first_name","last_name","email"]);
            $users_to=[];
            $emails_to=[];
            foreach ($users as $user) {
                array_push($users_to, $user->first_name." ".$user->last_name);
                array_push($emails_to, $user->email);
            }

            $emailSchedules->$method_name($emails_to, $users_to);
        }
    )->cron($report_schedule->frequency)->name('mail');
}