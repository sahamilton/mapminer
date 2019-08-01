<?php
namespace App;

class SalesOrg extends \Eloquent
{

    use Geocode;
    public $topdog = 2980;
    // Add your validation rules here
    public static $rules = [
        'title' => 'required'
    ];
    public $table = 'salesorgs';
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
        $topDog = Person::findOrFail($this->topdog);
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
        $topDog = Person::findOrFail($this->topdog);

        $salesteam = $topDog->descendants()->whereHas(
            'userdetails.roles', function ($q) {
                $q->whereIn('roles.id', [3,6,7]);
            }
        )->get();
        $team = $salesteam->map(
            function ($person) {
                return ['id'=>$person->id,'name'=>$person->fullName(),'reports_to'=>$person->reports_to];
            }
        );
        return $team->toJson();
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
