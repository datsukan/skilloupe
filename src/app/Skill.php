<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    /**
     * ネイティブなタイプへキャストする属性
     *
     * @var array
     */
    protected $casts = [
        'level'             => 'float',
        'experience_period' => 'float',
        'tool'              => 'array',
        'platform'          => 'array',
        'system'            => 'array',
    ];

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'level',
        'experience_period',
        'member',
        'tool',
        'platform',
        'system',
        'experience_detail',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
