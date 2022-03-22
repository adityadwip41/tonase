<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;

class TestProgramerController extends Controller
{
    public function index(){
       $kapal =  Kapal::orderby('CREATED_AT', 'DESC')->get();
        return view('test', compact('kapal'));
    }

    public function create(Request $request){
        if($request->number !== null){
            $number = $request->number;  
        }else{
            $number = rand(1000000,9999999);
        }
        $id =  $number;
        // $number = 1111151;
        $array  = array_map('intval', str_split($number));
        $x = $array[4];
        $x_ = $array[5];
        $dibagi = 0;
        $hasil = "";
      
        for ($i = 1; $i <= $number; $i++) {
            if ($number % $i == 0) {
                    $dibagi++;
            }
        }
        if ($dibagi == 2) {
                foreach ($array as  $key)
                {
                    if($key == 0){
                        $hasil = "DEAD";
                    }
                }
                if($hasil !== "DEAD"){
                    $c = 0;
                    $number = substr($number, 3);
                    for ($i = 1; $i <= $number; $i++) {
                        if ($number % $i == 0) {
                                $c++;
                        }
                    }
                    if($c == 2){
                        $hasil = "Central";
                        if(($x_ + 1) == $array[6]) {
                            $hasil = "LEFT";
                        }else if($x == $array[5] && $x == $array[6]){
                            $hasil = "RIGHT";
                        }
                    }else{
                        $hasil = "DEAD";
                    }
                }
                
            }else{
                $hasil = "DEAD";
        }

        Kapal::create([
            'id' => $id,
            'number_container' => $number,
            'position' => $hasil,
        ]);

        return response()->json([
            'data' => $number,
            'hasil' => $hasil,
            'SPLIT' => $array,
        ]);
    }

    public function delete($id){
        $kapal = Kapal::find($id)->first();
        $kapal->delete();
        return redirect()->back();
    }
}
