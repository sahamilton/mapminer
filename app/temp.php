$campaign = App\Campaign::with('companies', 'branches')->find(18);
$companies = $campaign->companies->pluck('id')->toArray();
$data['branches'] = App\Branch::whereHas('leads', function ($q) use ($companies) {
    $q->whereIn('company_id', $companies);
})->orderBy('branches.id')->pluck('id')->toarray();
$data['campaign']=$campaign->branches->sortBy('branches.id')->pluck('id')->toarray();
$notIn['campaign'] = array_diff($data['branches'], $data['campaign']);
$notIn['branches'] = array_diff($data['campaign'], $data['branches']


$people->descendantsAndSelf()
->withRoles([9])
->with(
['branchesServiced'=>function ($q) {
  $q->whereHas('servicelines', function($q) {
    $q->whereIn('servicelines.id', [5]);
  })
})->get();