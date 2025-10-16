<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'defi_id',
        'image',
        'type',
        'titre',
        'description',
        'date_evenement',
    ];

    /* Exemple de relation : un BookEvent appartient à un défi
      public function defi()
    {
        return $this->belongsTo(Defi::class);
   
}
       */
}
