<?php
namespace App;

class SalesOrg extends Model
{

    use Geocode;

    public $topdog = 2980;
    
    // Add your validation rules here
    public static $rules = [
        'title' => 'required'
    ];
    public $table = 'persons';
    // Don't forget to fill this array
    protected $fillable = ['title','name'];
    /**
     * [salesOrgRole description]
     *
     * @return relationship [<description>]
     */
    public function salesOrgRole()
    {
        return $this->hasMany(Person::class, 'position');
    }

    /**
     * [getSalesOrg description]
     * 
     * @return [type] [description]
     */
    public function getSalesOrg()
    {

        return Person::with('userdetails', 'userdetails.roles', 'userdetails.serviceline', 'industryfocus')
            ->whereHas(
                'userdetails.roles', function ($q) {
                    $q->where('id', '=', '5');
                }
            )
        ->whereNotNull('lat')
        ->get();
    }
    // Identify people who have sales rep role
    // but are not in the sales organization
    // hierarchy
    /**
     * [salesRepsOutsideOrg Identify people who have sales rep role
     * but are not in the sales organization
     * hierarchy
     * 
     * @return array Id of salesreps outside sales org
     */
    public function salesRepsOutsideOrg()
    {
        $topDog = $this->getCapoDiCapo();
        $salesReps = $topDog->allLeaves()->salesReps()->pluck('id')->toArray();

        $salesRoles = Person::salesReps()->pluck('id')->toArray();

        $diff = [];
        
        $diff['insiders'] = array_diff($salesRoles, $salesReps);
        return $diff;
    }
    /**
     * [getSalesOrgJson description]
     * 
     * @return [type] [description]
     */
    public function getSalesOrgJson()
    {
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        return $this->getCapoDiCapo()->descendants()->manages(array_keys($roles))->withPrimaryRole()->get();
        /*dd($topDog);
           
        // change this to go off rolename
        foreach ($roles as $key=>$role) {
            $salesteam[$role] = $topDog->descendants()
                ->withRoles([$key])
                ->selectRaw('concat_ws(" ", firstname, lastname) as fullname, id, reports_to as parent_id')
                ->get()->toJson();
        }
        return $salesteam;*/
    }
    /**
     * [getCapoDiCapo id the top of the sales org
     * refactor to programmatically get topdog.
     * 
     * @return Person topDog
     */
    public function getCapoDiCapo()
    {

        return Person::findOrFail($this->topdog);
    }
}
