<?php

namespace App\Http\Controllers;

use App\Employe;
use DB;
use Illuminate\Http\Request;
use Storage;

class EmployeController extends Controller
{
    public function get(Request $request)
    {
      if($request->paginate){
        return Employe::with(['position','civilstate', 'immediateBoss'])->/* latest()-> */paginate(5);
      }else{
        return Employe::with(['position','civilstate', 'immediateBoss'])->get();
      }
    }

    /* public function getImmediateBoss(Request $request)
    {
        $positionSelect = $request->position_id;
        $positionSelect--;
        dd($positionSelect);
        return Employe::with('position','civilstate', 'immediateBoss')
            ->where([['position_id', $positionSelect], ['estado_contrato', 1]])->get();
    } */

    /* Crear empleado */
    public function create(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            //decodificamos el request
            $requestData = json_decode($request->data);
            //proceso para guardar la imagen en storage
            $file = $request->foto;
            $nombreImagen = time() . '.' . $file->getClientOriginalExtension();

            //guardar datos en tabla employes
            $employe = new Employe();
            $employe->documento = $requestData->documento;
            $employe->nombres = $requestData->nombres;
            $employe->apellidos = $requestData->apellidos;
            $employe->fecha_nacimiento = $requestData->fecha_nacimiento;
            $employe->sexo = $requestData->sexo;
            $employe->foto = "storage/img/$nombreImagen";
            $employe->estado_contrato = $requestData->estado_contrato;
            $employe->civilstate_id = $requestData->civilstate_id;
            $employe->position_id = $requestData->position_id;
            if ($requestData->immediateboss_id === "") {
              $employe->immediateboss_id = null;
            }else{
              $employe->immediateboss_id = $requestData->immediateboss_id;
            }
            $employe->save();
            DB::commit();

            $file->move(public_path('/storage/img/'), $nombreImagen);
            return response()->json(['type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['type' => 'error'], 500);
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $employe = Employe::findOrFail($request->id);
            //decodificamos el request
            $requestData = json_decode($request->data);

            //Validar si viene imagen
            if ($request->foto) {
                //borrar foto actual del storage
                $fileActually = $employe->foto;
                unlink(public_path('/'.$fileActually));

                //proceso para guardar la imagen en storage
                $file = $request->foto;
                $nombreImagen = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/storage/img/'), $nombreImagen);
                $employe->foto = "storage/img/$nombreImagen";
            }

            //guardar datos en tabla employes
            $employe->documento = $requestData->documento;
            $employe->nombres = $requestData->nombres;
            $employe->apellidos = $requestData->apellidos;
            $employe->fecha_nacimiento = $requestData->fecha_nacimiento;
            $employe->sexo = $requestData->sexo;
            $employe->estado_contrato = $requestData->estado_contrato;
            $employe->civilstate_id = $requestData->civilstate_id;
            $employe->position_id = $requestData->position_id;
            if ($requestData->immediateboss_id === "") {
              $employe->immediateboss_id = null;
            }else{
              $employe->immediateboss_id = $requestData->immediateboss_id;
            }
            $employe->save();

            DB::commit();
            return response()->json(['type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['type' => 'error'], 500);
        }
    }

    /* para eliminar registro en bd */
    public function delete(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //obtenemos el empleado elegido para eliminar
        $employe = Employe::with('position')->findOrFail($request->id);

        //buscamos si tiene personal a cargo
        $searchImmediate = collect(Employe::where('immediateboss_id', $employe->id)->get());

        if ($searchImmediate->count() === 0) {
            //borrar foto actual del storage
            $fileActually = $employe->foto;
            unlink(public_path('/'.$fileActually));

            $employe->delete();
            return response()->json(
                ['type' => 'success',
                'message' => 'Empleado eliminado con éxito'], 200);
        }

        return response()->json(
            ['type' => 'warning',
            'message' => 'El ' . $employe->position->nombre . ' tiene personal a cargo'], 200);
    }
}
