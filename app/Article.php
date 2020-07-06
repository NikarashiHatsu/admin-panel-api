<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_penulis', 'foto_depan', 'redaksi', 'published',
    ];

    /**
     * Mengambil info penulis
     */
    public function writer()
    {
        return $this->hasOne('App\User', 'id', 'id_penulis');
    }
}
