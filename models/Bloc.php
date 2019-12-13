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

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_publisher_blocs';

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
    public $hasMany = [];
    public $belongsTo = [
        'document' => ['Waka\Publisher\Models\Document'],
        'bloc_type' => ['Waka\Publisher\Models\BlocType'],
    ];
    public $belongsToMany = [];
    public $morphTo = [
        'obj' => [],
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * EVENT
     */

    /**
     * GET
     */
}
