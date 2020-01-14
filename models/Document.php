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

    use \October\Rain\Database\Traits\Sluggable;
    protected $slugs = ['slug' => 'name'];

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
    public $rules = [
        'path' => 'required',
        'data_source' => 'required',
        'name' => 'required',
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
        'blocs' => ['Waka\Publisher\Models\Bloc']
    ];
    public $belongsTo = [
        'data_source' => ['Waka\Utils\Models\DataSource']
    ];
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
        $wp = new WordProcessor($this->id);
        $wp->checkTags();
    }
    public function beforeSave()
    {
        
    }
    //
    public function listContacts()
    {
        return \waka\Crsm\Models\Contact::lists('name', 'id');
    }
}
