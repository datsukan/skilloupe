<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * ネイティブなタイプへキャストする属性
     *
     * @var array
     */
    protected $casts = [
        'system'        => 'array',
        'region'        => 'array',
        'role'          => 'array',
        'dev_method'    => 'array',
        'process'       => 'array',
        'lang_and_fw'   => 'array',
        'os_and_mw'     => 'array',
        'tool'          => 'array',
    ];

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'started_at',
        'ended_at',
        'summary',
        'member',
        'system',
        'region',
        'role',
        'dev_method',
        'process',
        'lang_and_fw',
        'os_and_mw',
        'tool',
        'experience_detail',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
