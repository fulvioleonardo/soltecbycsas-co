<?php

namespace Modules\Factcolombia1\Http\Controllers\Tenant;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Factcolombia1\Http\Controllers\Controller;
use Modules\Factcolombia1\Exports\Tenant\TaxesExport;
use Modules\Factcolombia1\Http\Requests\Tenant\{
    TaxUpdateRequest,
    TaxRequest
};
use Illuminate\Http\Request;
use Modules\Factcolombia1\Models\Tenant\{
    Company,
    Tax
};
use Modules\Factcolombia1\Models\TenantService\{
    Tax as TypeTaxes
};

use Modules\Factcolombia1\Http\Resources\Tenant\TaxCollection;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('factcolombia1::tax.tenant.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'type_tax_id_name' => 'Tipo Impuesto',
        ];
    }

    public function records(Request $request)
    {
        $records = Tax::where($request->column, 'like', "%{$request->value}%");

        return new TaxCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = Tax::findOrFail($id);

        return $record;
    }

    public function tables() {
        return [
            'type_taxes' => TypeTaxes::all(),
            'taxes_in_tax' => Tax::all()
        ];
    }

    /**
     * All
     * @return \Illuminate\Http\Response
     */
    public function all() {
        return [
            'company' => Company::query()
                ->with('currency')
                ->firstOrFail(),
            'taxes' => Tax::query()
                ->with('tax')
                ->get(),
            'type_taxes' => TypeTaxes::all()
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Tenant\TaxRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxRequest $request) {
        $tax = Tax::create([
            'name' => strtoupper($request->name),
            'code' => $request->code,
            'rate' => $request->rate ?? 0,
            'conversion' => $request->conversion,
            'is_percentage' => $request->is_percentage,
            'is_fixed_value' => $request->is_fixed_value,
            'is_retention' => $request->is_retention,
            'in_base' => $request->in_base,
            'in_tax' => $request->in_tax,
            'type_tax_id' => $request->type_tax_id
        ]);

        return [
            'success' => true,
            'message' => "Se registro con éxito el impuesto {$tax->name}."
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Requests\Tenant\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(TaxUpdateRequest $request, Tax $tax) {
        $tax->name = strtoupper($request->name);
        $tax->code = $request->code;
        $tax->rate = $request->rate ?? 0;
        $tax->conversion = $request->conversion;
        $tax->is_percentage = $request->is_percentage;
        $tax->is_fixed_value = $request->is_fixed_value;
        $tax->is_retention = $request->is_retention;
        $tax->in_base = $request->in_base;
        $tax->in_tax = $request->in_tax;
        $tax->type_tax_id = $request->type_tax_id;

        $tax->save();

        return [
            'success' => true,
            'message' => "Se actualizo con éxito el impuesto {$tax->name}."
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Tenant\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax) {
        $tax->forceDelete();

        return [
            'success' => true,
            'message' => "Se elimino con éxito el impuesto {$tax->name}."
        ];
    }

    /**
     * Export
     * @return \Illuminate\Http\Response
     */
    public function export() {
        return Excel::download(new TaxesExport, 'impuestos.xlsx');
    }
}
