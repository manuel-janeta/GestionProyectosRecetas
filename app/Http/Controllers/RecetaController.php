<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{

    //costructor
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']); //verifica que se haya relizado la autentificación
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Capturar el id del usuario autentificado
        $userRecetas = Auth::user()->userRecetas;
        return view('recetas.index')->with('userRecetas', $userRecetas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //consulta categorias 
        //Obtener categorias (sin modelo)
        //$categorias = DB::table('categorias')->get()->pluck('nombre', 'id');
        //Obtener categorias (con modelo)
        $categorias = Categoria::all(['id', 'nombre']);
        return view('recetas.create')->with('categorias', $categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request['imagen']->store('upload-recetas','public'));

        //validaciones
        $data = $request->validate([
            'nombre' => 'required|min:6',
            'categoria' => 'required',
            'ingredientes' => 'required',
            'preparacion' => 'required',
            'imagen' => 'required|image'
        ]);

        //variable para la ruta de la imagen
        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

        //Redimensionar la imagen
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
        //Guardar en el disco duro del sevidor
        $img->save();

        //Almacenar en la BDD (sin modelo)
        /* DB::table('recetas')->insert([
            'nombre' => $data['nombre'],
            'ingredientes' => $data['ingredientes'],
            'preparacion' => $data['preparacion'],
            'imagen' => $ruta_imagen,
            'user_id' => Auth::user()->id, //capturar el id de usuario
            'categoria_id' => $data['categoria']
        ]);*/

        //Almacenar en la BDD (con modelo)
        Auth::user()->userRecetas()->create([
            'nombre' => $data['nombre'],
            'ingredientes' => $data['ingredientes'],
            'preparacion' => $data['preparacion'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);


        //redireccion
        return Redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        return view('recetas.show')->with('receta', $receta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        $categorias = Categoria::all(['id', 'nombre']);
        return view('recetas.edit')->with('categorias', $categorias)
            ->with('receta', $receta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        //verificar policy
        $this->authorize('update', $receta);

        //validar campos
        $data = $request->validate([
            'nombre' => 'required|min:6',
            'categoria' => 'required',
            'ingredientes' => 'required',
            'preparacion' => 'required',
        ]);
        //asignando valores
        $receta->nombre = $data['nombre'];
        $receta->categoria_id = $data['categoria'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->preparacion = $data['preparacion'];
        

        //Nueva imagen
        if (request('imagen')) {
            //variable para la ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

            //Redimensionar la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
            //Guardar en el disco duro del sevidor
            $img->save();
            //Asignar valor de la Imagen
            $receta->imagen = $ruta_imagen;
        }
        //Guardamos la info en la bdd
        $receta->save();
        return Redirect()->action([RecetaController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        $this->authorize('delete', $receta);
        $receta->delete();
        return Redirect()->action([RecetaController::class, 'index']);
    }
}
