<?php

namespace App\Http\Controllers;

use App\Employe;
use DB;
use Illuminate\Http\Request;
use Storage;

class EmployeController extends Controller
{
    public function get()
    {
        return Employe::with(['position','civilstate', 'immediateBoss'])->paginate(5);
    }

    public function getImmediateBoss(Request $request)
    {
        $positionSelect = $request->position_id;
        $positionSelect--;
        return Employe::with('position','civilstate', 'immediateBoss')
            ->where([['position_id', $positionSelect], ['estado_contrato', 1]])->get();
    }

    /* Crear empleado */
    public function create(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();

            //proceso para guardar la imagen en storage
            $file = $request->foto;
            $nombreImagen = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/storage/img/'), $nombreImagen);

            //guardar datos en tabla employes
            $employe = new Employe();
            $employe->documento = $request->data['documento'];
            $employe->nombres = $request->data['nombres'];
            $employe->apellidos = $request->data['apellidos'];
            $employe->fecha_nacimiento = $request->data['fecha_nacimiento'];
            $employe->sexo = $request->data['sexo'];
            $employe->foto = "storage/img/$nombreImagen";
            $employe->estado_contrato = $request->data['estado_contrato'];
            $employe->civilstate_id = $request->data['civilstate_id'];
            $employe->position_id = $request->data['position_id'];
            $employe->immediateboss_id = $request->data['immediateboss_id'];
            $employe->save();

            return response()->json(['type' => 'success'], 200);
            DB::commit();
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

            //Validar si viene imagen
            if ($request->foto) {
                //borrar foto actual del storage
                $fileActually = $employe->foto;
                Storage::delete($fileActually);
                //proceso para guardar la imagen en storage
                $file = $request->foto;
                $nombreImagen = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/storage/img/'), $nombreImagen);
                $employe->foto = "storage/img/$nombreImagen";
            }

            //guardar datos en tabla employes
            $employe->documento = $request->data['documento'];
            $employe->nombres = $request->data['nombres'];
            $employe->apellidos = $request->data['apellidos'];
            $employe->fecha_nacimiento = $request->data['fecha_nacimiento'];
            $employe->sexo = $request->data['sexo'];
            $employe->estado_contrato = $request->data['estado_contrato'];
            $employe->civilstate_id = $request->data['civilstate_id'];
            $employe->position_id = $request->data['position_id'];
            $employe->immediateboss_id = $request->data['immediateboss_id'];
            $employe->save();

            return response()->json(['type' => 'success'], 200);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['type' => 'error'], 500);
        }
    }

    /* para eliminar registro en bd */
    public function delete(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $employe = Employe::with('position')->findOrFail($request->id);

        //buscamos si tiene personal a cargo
        $searchImmediate = collect(Employe::where('immediateboss_id', $employe->id)->get());

        if ($searchImmediate->count() === 0) {
            $employe->delete();
            return response()->json(
                ['type' => 'success',
                'message' => 'Empleado eliminado con éxito'], 200);
        }

        return response()->json(
            ['type' => 'warning',
            'message' => 'El ' . $employe->position->nombre . ' tiene personal a cargo'], 422);
    }
}
