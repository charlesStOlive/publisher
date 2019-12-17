<?php namespace Waka\Publisher\Models;

use Model;
use \Waka\Publisher\Classes\WordProcessor;

/**
 * Document Model
 */
class Document extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;
    //
    use \Waka\Informer\Classes\Traits\InformerTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_publisher_documents';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

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
        'blocs' => ['Waka\Publisher\Models\Bloc']
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [
        'informs' => ['Waka\Informer\Models\Inform', 'name' => 'informeable']
    ];
    public $attachOne = [];
    public $attachMany = [];

    public function afterCreate()
    {
        
        $tags = WordProcessor::checkTags($this->id, 'create');
    }
    public function beforeSave()
    {
        //$this->state_info = 'warning';
        if($this->id) {
            $tags = WordProcessor::checkTags($this->id);
        }
        
    }
    //
    public function listContacts()
    {
        return \waka\Crsm\Models\Contact::lists('name', 'id');
    }
}
