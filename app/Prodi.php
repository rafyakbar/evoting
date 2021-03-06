<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'jurusan_id'
    ];

    /**
     * Mendapatkan data mahasiswa dengan prodi ini
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getMahasiswa($queryReturn = true)
    {
        $data = $this->hasMany('App\Mahasiswa', 'prodi_id');
        return $queryReturn ? $data : $data->get();
    }

    /**
     * Mendapatkan data jurusan dari prodi ini
     * @return Model|null|static
     */
    public function getJurusan($queryReturn = true)
    {
        $data = $this->belongsTo('App\Jurusan', 'jurusan_id');
        return $queryReturn ? $data : $data->first();
    }

    /**
     * Mendapatkan data prodi dari nama
     * @param $nama
     * @return mixed
     */
    public static function getByName($nama)
    {
        return Prodi::where('nama', $nama)->first();
    }
}
