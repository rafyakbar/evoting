<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Array_;
use Carbon\Carbon;

class CalonBEM extends Model
{
    protected $table = 'calon_bem';

    public $timestamps = false;

    protected $fillable = [
        'ketua_id', 'wakil_id', 'dir', 'visi', 'misi', 'nomor'
    ];

    /**
     * mengambil data mahasiswa yang memilih
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPemilih($queryReturn = true)
    {
        $data = $this->belongsToMany('App\Mahasiswa', 'pemilihan_bem', 'calon_bem_id', 'mahasiswa_id')->withTimestamps()->where('status', Mahasiswa::AKTIF);
        return $queryReturn ? $data : $data->get();
    }

    /**
     * @param bool $queryReturn
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPemilihUnique($queryReturn = true)
    {
        $data = Mahasiswa::query()->whereIn('id', function ($query) {
            $query->select('mahasiswa_id')
                ->from('pemilihan_bem')
                ->where('calon_bem_id', $this->id)
                ->groupBy('mahasiswa_id');
        });

        return $queryReturn ? $data : $data->get();
    }

    /**
     * mengambil data ketua
     * @return Model|null|static
     */
    public function getKetua($queryReturn = true)
    {
        $data = $this->belongsTo('App\Mahasiswa', 'ketua_id');
        return $queryReturn ? $data : $data->first();
    }

    /**
     * mengambil data wakil
     * @return Model|null|static
     */
    public function getWakil($queryReturn = true)
    {
        $data = $this->belongsTo('App\Mahasiswa', 'wakil_id');
        return $queryReturn ? $data : $data->first();
    }

    /**
     * Mendapatkan data jumlah pemilih pada jam-jam tertentu
     * yang akan ditampilkan dalam bentuk diagram batang
     *
     * @param int $jurusan_id
     * @return array
     */
    public static function getJumlahVotingBarChart()
    {
        $data = [];

        $waktuMulai = Pengaturan::getWaktuMulai()->minute == 0 ? Pengaturan::getWaktuMulai() : Pengaturan::getWaktuMulai()->addMinutes(-Pengaturan::getWaktuMulai()->minute);

        while ($waktuMulai->lessThan(Pengaturan::getWaktuSelesai())) {
            // generate waktuSelesai
            $waktuSelesai = $waktuMulai->copy()->addMinutes(60);

            $jumlahVoting = Mahasiswa::getYangTelahMemilihBemViaRelation($waktuMulai, $waktuSelesai)->count();

            array_push($data, [
                Carbon::parse($waktuMulai)->hour . ':00' . '-' . Carbon::parse($waktuSelesai)->hour . ':00',
                $jumlahVoting
            ]);

            $waktuMulai = $waktuSelesai;
        }

        return collect($data);
    }

    /**
     * Mendapatkan hasil dalam bentuk array yang nantinya akan digunakan
     * untuk diagram
     *
     * @param int $jurusan_id
     * @return array
     */
    public static function getHasilUntukDiagram()
    {
        $data = [];
        $daftar_calon = static::all();

        foreach ($daftar_calon as $calon) {
            $data['Nomor Paslon ' . $calon->nomor] = $calon->getPemilihUnique()->count();
        }

        $data['Abstain'] = Mahasiswa::getAbstainBemViaRelation()->count();

        return $data;
    }

}
