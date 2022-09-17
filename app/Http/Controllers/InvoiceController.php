<?php

namespace App\Http\Controllers;

use App\Imports\InvoiceImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function export(){
        return view('invoices.export');
    }

    public function import(){
        return view('invoices.import');
    }

    public function importStore(Request $request){

        $request->validate([
            'file' => 'required|mimes:csv,xlsx'
        ]);

        $file = $request->file('file');

        Excel::import(new InvoiceImport, $file);

        session()->flash('flash.banner', 'Facturas importadas correctamente');
        session()->flash('flash.bannerStyle', 'success');


        return redirect()->route('invoices.import');

    }
}
