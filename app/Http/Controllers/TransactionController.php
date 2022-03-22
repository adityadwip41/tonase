<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Saldo;
use App\Models\Rekening;
use App\Models\Mutasi;
use App\User;

class TransactionController extends Controller
{
    public function rekening(Request $request){
        $akun = User::find(auth()->user()->id);
        try {
            $rekening = Rekening::create([
                'user_id' => $akun->id,
                'nama_rek' => $request->nama_rek,
                'no_rek' => $request->no_rek,
            ]);
            return response()->json([
                'status' => 200,
                'data' => $rekening
            ]);
          } catch (\Execption $e) {
              return response()->json([
                  'status' => 400,
                  'data' => $e->getMessage()
              ]);
          }
    }

    public function topup(Request $request, $rek){
        $akun = User::find(auth()->user()->id);
        try {
            $user = Saldo::where([
                'user_id' => $akun->id,
            ])->first();

            if(!empty($user)){
                $no_rek = Rekening::where([
                    'user_id' =>  $akun->id,
                    'no_rek' => $rek
                    ])->first();
                    if(!empty($no_rek)){
                        $user->update([
                            'saldo' => $user->saldo + $request->topup
                        ]);
                        Mutasi::create([
                            'user_id' => $akun->id,
                            'no_rek' => $no_rek->no_rek,
                            'jenis_transaksi' => "Top-up",
                            'nominal' => $request->topup
                        ]);
                        return response()->json([
                            'status' => 200,
                            'data' => $user
                        ]);
                    }else{
                        return response()->json([
                            'status' => 200,
                            'message' => "No Rekening Tidak Di temukan!",
                        ]);
                    }
            }else{
                return response()->json([
                    'status' => 200,
                    'message' => "User Tidak di temukan!",
                ]);
            }

        } catch (\Execption $e) {
            return response()->json([
                'status' => 400,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function withdraw(Request $request, $rek){
        $akun = User::find(auth()->user()->id);
        try {

            $saldo = Saldo::where('user_id', $akun->id)->first();

            if(!empty($saldo)){
                $no_rek = Rekening::where([
                    'user_id' =>  $akun->id,
                    'no_rek' => $rek
                    ])->first();
                if(!empty($no_rek)){
                        $withdraw =  $request->withdraw;
                        if($saldo->saldo > $withdraw){
                            $saldo->update([
                                'saldo' => $saldo->saldo - $withdraw,
                            ]);
                            Mutasi::create([
                                'user_id' => $akun->id,
                                'jenis_transaksi' => "WITHDRAW",
                                'no_rek' => $no_rek->no_rek,
                                'nominal' => $withdraw
                            ]);
                            return response()->json([
                                'status' => 200,
                                'message' => "Berhasil withdraw ". $withdraw. " Saldo sisa ". $saldo->saldo,
                                'data' => $saldo
                            ]);
                        } else{
                            return response()->json([
                                'status' => 200,
                                'message' => "Saldo Tidak Mencukupi, saldo hanya tersisa ". $saldo->saldo,
                                'data' => $saldo
                            ]);
                        }
                    
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' => "No Rekening Tidak Di temukan!",
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 200,
                    'message' => "User Tidak di temukan!",
                ]);
            }
          } catch (\Execption $e) {
              return response()->json([
                  'status' => 400,
                  'data' => $e->getMessage()
              ]);
          }
    }

    public function transfer(Request $request, $rek){
        $akun = User::find(auth()->user()->id);
        try {
            $saldo = Saldo::where('user_id',  $akun->id)->first();
            if(!empty($saldo)){
                $no_rek = Rekening::where([
                    'user_id' =>  $akun->id,
                    'no_rek' => $rek
                    ])->first();
                if(!empty($no_rek)){
                    $rekening_penerima = Rekening::where('no_rek', 'like', '%' . $request->no_rek . '%')->first();
                    if(!empty($rekening_penerima)){
                        $saldo_penerima = Saldo::where('user_id', $rekening_penerima->user_id)->first();
                        if($saldo->user_id !== $saldo_penerima->user_id){
                            $transfer =  $request->transfer;
                            if($saldo->saldo > $transfer){
                                $saldo->update([
                                    'saldo' => $saldo->saldo - $transfer,
                                ]);
                                
                                Mutasi::create([
                                    'user_id' => $akun->id,
                                    'no_rek'=> $no_rek->no_rek,
                                    'jenis_transaksi' => "PENGELUARAN",
                                    'nominal' => $transfer
                                ]);

                                $saldo_penerima->update([
                                    'saldo' => $saldo_penerima->saldo + $transfer,
                                ]);

                                Mutasi::create([
                                    'user_id' => $saldo_penerima->user_id,
                                    'jenis_transaksi' => "PEMASUKAN",
                                    'nominal' => $transfer
                                ]);

                                return response()->json([
                                    'status' => 200,
                                    'message' => "Berhasil Transfer ". $transfer. " Saldo tersisa ".$saldo->saldo,
                                    'data' => $saldo
                                ]);
                            }else{
                                return response()->json([
                                    'status' => 200,
                                    'message' => "Saldo Tidak Mencukupi, saldo hanya tersisa ". $saldo->saldo,
                                    'data' => $saldo
                                ]);
                            }
                        }else{
                            return response()->json([
                                'status' => 200,
                                'message' => "No Rekening Tujuan Tidak Di temukan!",
                            ]);
                        } 
                    }else{
                        return response()->json([
                            'status' => 200,
                            'message' => "No Rekening Tujuan Tidak Di temukan!",
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' => "No Rekening Tidak Di temukan!",
                    ]);
                }

            }else{
                return response()->json([
                    'status' => 200,
                    'message' => "User Tidak di temukan!",
                ]);
            }
          } catch (\Execption $e) {
              return response()->json([
                  'status' => 400,
                  'data' => $e->getMessage()
              ]);
          }

    }

    public function mutasi($rek){

        try {
            $akun = User::find(auth()->user()->id);
            $user = Saldo::where('user_id', $akun->id)->first();
            if(!empty($user)){

                $mutasi = Mutasi::where([
                    'user_id' => $akun->id,
                    'no_rek' => $rek
                    ])->get();
    
                if(!empty($mutasi)){
                    return response()->json([
                        'status' => 200,
                        'data' => $mutasi
                    ]);
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' => "No Rekening Tidak di temukan!",
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 201,
                    'message' => "User Tidak di temukan!",
                ]);
            }
        } catch (\Execption $e) {
            return response()->json([
                'status' => 400,
                'data' => $e->getMessage()
            ]);
        }
    }
}
