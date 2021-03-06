<?php namespace Waka\Publisher\Models;

use Model;

/**
 * Bloc Model
 */
class Bloc extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    use \Waka\Informer\Classes\Traits\InformerTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_publisher_blocs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name, code'];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'code' => 'required',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'contents' => ['Waka\Publisher\Models\Content']
    ];
    public $belongsTo = [
        'document' => ['Waka\Publisher\Models\Document'],
        'bloc_type' => ['Waka\Publisher\Models\BlocType'],
    ];
    public $belongsToMany = [];
    public $morphTo = [
        'obj' => [],
    ];
    public $morphOne = [];
    public $morphMany = [
        'informs' => ['Waka\Informer\Models\Inform', 'name' => 'informeable']
    ];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * EVENT
     */
    public function afterCreate() {
        if(!count($this->contents)>0) {
            $this->record_inform('problem', 'le bloc est vide !' );
        } 
    }
    /**
     * GET
     */
}
