<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Carrito;

class CarritoController extends Controller
{
    public function index()
    {
        $listaProducto = DB::table('producto')
                        ->select('idProducto','titulo','descripcion', 'marca',
                                 'precioVenta', 'cantidad')
                        ->where('estatus', '=', 1)
                        ->get();    
        
        //print_r($empleado);
        return view('Productosindex', ['listaProducto' => $listaProducto]);
    }

    public function agregarProductoCarrito(Request $request){
        $carrito = new Carrito();

        $cantidadProducto = DB::table('producto')
                                ->select('cantidad')
                                ->where('idProducto', '=', $request->idProducto)
                                ->get();
        foreach ($cantidadProducto as $value) {

            $cantidadDisponible = $value->cantidad;
        }
        
        if($request->cantidad <= $cantidadDisponible){
            
            $data = DB::select('call  sp_InsertarCarrito(?,?,?,?,@var_idCarrito)', 
                    array(auth()->user()->id,$request->idProducto,$request->cantidad,1));
            $id = DB::select('select @var_idCarrito as idCarrito');

            return redirect($request->ruta);
        }
        else{
            return redirect('/indexProducto');
        }
    }

    public function vistaProductosCarrito()
    {
        $listaProductoCarrito = DB::table('carrito')
            ->join('producto', 'carrito.idProducto', '=', 'producto.idProducto')
            ->join('imagenProducto', 'producto.idProducto', '=', 'imagenProducto.idProducto')
            ->select('carrito.idCarrito','producto.idProducto','producto.titulo',
                    'producto.descripcion','producto.marca', 'producto.precioVenta',
                    'carrito.cantidadProducto','imagenProducto.imagenUrl','producto.precioVenta','producto.descuentoVenta','producto.cantidad')
            ->where(function($query)
            {
                $query->where('carrito.idUsuario', '=', auth()->user()->id)
                        ->where('carrito.estatus', '=', 1);
            })
            ->get();
        
        $listaPendiente = DB::table('carrito')
            ->join('producto', 'carrito.idProducto', '=', 'producto.idProducto')
            ->join('imagenProducto', 'producto.idProducto', '=', 'imagenProducto.idProducto')
            ->select('carrito.idCarrito','producto.idProducto','producto.titulo',
                    'producto.descripcion','producto.marca', 'producto.precioVenta',
                    'carrito.cantidadProducto','imagenProducto.imagenUrl','producto.precioVenta','producto.descuentoVenta','producto.cantidad')
            ->where(function($query)
            {
                $query->where('carrito.idUsuario', '=', auth()->user()->id)
                        ->where('carrito.estatus', '=', 2);
            })
            ->get();
        return view('Carrito', ['listaProductoCarrito' => $listaProductoCarrito], ['listaPendiente' => $listaPendiente]);
    }

    public function destroy($idCarrito){
        
        $productoCarritoEliminar = Carrito::findOrFail($idCarrito);
        $productoCarritoEliminar->estatus = 0;

        $productoCarritoEliminar -> save();
        return redirect('/Carrito');
    }


    public function guardar($idCarrito){
        
        $productoCarritoGuardar = Carrito::findOrFail($idCarrito);
        $productoCarritoGuardar->estatus = 2;

        $productoCarritoGuardar -> save();
        return redirect('/Carrito');
    }

    public function asignarCompra($idCarrito){
        
        $productoCarritoComprar = Carrito::findOrFail($idCarrito);
        $productoCarritoComprar->estatus = 1;

        $productoCarritoComprar -> save();
        return redirect('/Carrito');

    }
}
