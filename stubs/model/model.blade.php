namespace App\Models;

@if( $authenticatable )
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use LaravelRocket\Foundation\Models\AuthenticatableBase;
@else
use LaravelRocket\Foundation\Models\Base;
@endif
@if( $softDelete )
use Illuminate\Database\Eloquent\SoftDeletes;
@endif

/**
 * App\Models\{{ $className }}.
 *
 * ＠method \App\Presenters\{{ $className }}Presenter present()
 *
 */

class {{ $className }} extends {{ $authenticatable ? 'AuthenticatableBase' : 'Base' }}
{

@if( $softDelete )
    use SoftDeletes;
@endif
@if( $authenticatable )
    use HasApiTokens, Notifiable;
@endif

@foreach( $constants as $constant )
    const {!! $constant !!};
@endforeach

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '{{ $tableName }}';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
@foreach( $fillables as $fillable)
        '{{ $fillable }}',
@endforeach
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [
@foreach( $timestamps as $timestamp)
        '{{ $timestamp }}',
@endforeach
    ];

    protected $casts     = [
@foreach( $casts as $column => $type)
        '{{ $column }}' => '{{ $type }}',
@endforeach
    ];

    protected $presenter = \App\Presenters\{{ $className }}Presenter::class;

    // Relations
@foreach( $relations as $relation)
@if( $relation->getType() === 'belongsTo')
    public function {{ $relation->getName() }}()
    {
        return $this->belongsTo(\App\Models\{{ $relation->getReferenceModel() }}::class, '{{ $relation->getReferenceColumn()->getName() }}', '{{ $relation->getColumn()->getName() }}');
    }

@elseif( $relation->getType() === 'hasMany')
    public function {{ $relation->getName() }}()
    {
        return $this->hasMany(\App\Models\{{ $relation->getReferenceModel() }}::class, '{{ $relation->getReferenceColumn()->getName() }}', '{{ $relation->getColumn()->getName() }}');
    }

@elseif( $relation->getType() === 'hasOne')
    public function {{ $relation->getName() }}()
    {
        return $this->hasOne(\App\Models\{{ $relation->getReferenceModel() }}::class, '{{ $relation->getReferenceColumn()->getName() }}', '{{ $relation->getColumn()->getName() }}');
    }

@elseif( $relation->getType() === 'belongsToMany')
    public function {{ $relation->getName() }}()
    {
        return $this->belongsToMany(\App\Models\{{ $relation->getReferenceModel() }}::class, '{{ $relation->getReferenceTableName() }}', '{{ $relation->getReferenceColumn()->getName() }}', '{{ $relation->getColumn()->getName() }}');
    }

@endif
@endforeach

    // Utility Functions

}
