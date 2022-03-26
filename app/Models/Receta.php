<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ingredientes',
        'preparacion',
        'imagen',
        'categoria_id',
    ];

    //Relación: Obtener la información de la categoría mediante la clave foránea
    public function categoriaReceta(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    //Relación: Obtener la información del usuario que creó la receta mediante la clave foránea
    public function autorReceta(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
