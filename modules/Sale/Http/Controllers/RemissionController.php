<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Exception;
use Modules\Sale\Models\Remission;
use Modules\Sale\Http\Resources\RemissionCollection;
use Modules\Sale\Http\Resources\RemissionResource;
use Modules\Sale\Http\Requests\RemissionRequest;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
    Tax,
    PaymentMethod,
    PaymentForm,
    Company
};
use App\Http\Controllers\Tenant\{
    PersonController,
    ItemController,
};
use Modules\Factcolombia1\Helpers\DocumentHelper;


class RemissionController extends Controller
{

    use StorageDocument;

    protected $remission;

    public function index()
    {
        return view('sale::co-remissions.index');
    }

    public function create($id = null)
    {
        return view('sale::co-remissions.form', compact('id'));
    }
 
    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión',
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {

        $records = Remission::where($request->column, 'like', "%{$request->value}%")
                                ->latest();

        return new RemissionCollection($records->paginate(config('tenant.items_per_page')));
    }

    
    public function tables() 
    {
        $customers = $this->table('customers');
        $payment_methods = PaymentMethod::all();
        $payment_forms = PaymentForm::all();
        $currencies = Currency::all();
        $taxes = $this->table('taxes');

        return compact('customers', 'payment_methods', 'payment_forms', 'currencies', 'taxes');
    }


    public function item_tables()
    {
        $items = $this->table('items');
        $taxes = $this->table('taxes');

        return compact('items', 'taxes');
    }


    public function table($table)
    { 

        if ($table === 'customers') {
            $persons = app(PersonController::class)->searchCustomers(new Request());
            return $persons['customers'];
        }

        if ($table === 'taxes') {
            return Tax::all()->transform(function($row) {
                return $row->getSearchRowResource();
            });
        }

        if ($table === 'items') {
            $items = app(ItemController::class)->searchItems(new Request());
            return $items['items'];
        }

    }


    public function record($id)
    {
        $record = new RemissionResource(Remission::findOrFail($id));

        return $record;
    }
 

    public function store(RemissionRequest $request) {

        DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);

            $this->remission =  Remission::updateOrCreate( ['id' => $request->input('id')], $data);

            $this->remission->items()->delete();

            foreach ($data['items'] as $row) {
                $this->remission->items()->create($row);
            }

            $this->setFilename();
            $this->createPdf();

        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->remission->id,
            ],
        ];
    }
 

    public function mergeData($inputs)
    {

        $establishment_id = auth()->user()->establishment_id;
        $items = DocumentHelper::getDataItemFromInputs($inputs);
        // dd($items);

        $values = [
            'user_id' => auth()->id(),
            'external_id' => ($inputs->id) ? $inputs->external_id : Str::uuid()->toString(),
            'customer' => Person::with('typePerson', 'typeRegime', 'identity_document_type', 'country', 'department', 'city')->findOrFail($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($establishment_id),
            'establishment_id' => $establishment_id,
            'state_type_id' => '01',
            'number' => $this->getNumber(),
            'items' => $items,
        ];

        $inputs->merge($values);

        return $inputs->all();
    }
    
    /**
     * Obtener ultimo numero correlativo
     *
     * @return int
     */
    private function getNumber()
    {
        $remission = Remission::select('number')->latest()->first();

        return ($remission) ? (int) $remission->number + 1 : 1;
    }


    private function setFilename()
    {
        $name = [$this->remission->prefix,$this->remission->number,date('Ymd')];
        $this->remission->filename = join('-', $name);
        $this->remission->save();
    }


    public function toPrint($external_id, $format) 
    {
        $remission = Remission::where('external_id', $external_id)->first();
        if (!$remission) throw new Exception("El código {$external_id} es inválido, no se encontro el registro relacionado");

        // $this->createPdf($remission, $format, $remission->filename);
        $temp = tempnam(sys_get_temp_dir(), 'remission');

        file_put_contents($temp, $this->getStorage($remission->filename, 'remission'));

        return response()->file($temp);
    }

    

    public function createPdf($remission = null, $format_pdf = 'a4', $filename = null) 
    {
     
        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $document = ($remission != null) ? $remission : $this->remission;
        $company = Company::first();
        $filename = ($filename != null) ? $filename : $this->remission->filename;

        $base_template = config('tenant.pdf_template');

        $html = $template->pdf($base_template, "remission", $company, $document, $format_pdf);

        $pdf_font_regular = config('tenant.pdf_name_regular');
        $pdf_font_bold = config('tenant.pdf_name_bold');

        if ($pdf_font_regular != false) {
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $pdf = new Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                DIRECTORY_SEPARATOR.'pdf'.
                                                DIRECTORY_SEPARATOR.$base_template.
                                                DIRECTORY_SEPARATOR.'font')
                ]),
                'fontdata' => $fontData + [
                    'custom_bold' => [
                        'R' => $pdf_font_bold.'.ttf',
                    ],
                    'custom_regular' => [
                        'R' => $pdf_font_regular.'.ttf',
                    ],
                ]
            ]);
        }

        $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                             DIRECTORY_SEPARATOR.'pdf'.
                                             DIRECTORY_SEPARATOR.$base_template.
                                             DIRECTORY_SEPARATOR.'co_custom_styles.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        if(config('tenant.pdf_template_footer')) {
            $html_footer = $template->pdfFooter($base_template);
            $pdf->SetHTMLFooter($html_footer);
        }

        $this->uploadFile($filename, $pdf->output('', 'S'), 'remission');
    }


    public function uploadFile($filename, $file_content, $file_type) 
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }


    public function download($external_id, $format = 'a4') 
    {
        $remission = Remission::where('external_id', $external_id)->first();

        if (!$remission) throw new Exception("El código {$external_id} es inválido, no se encontro el documento relacionado");

        return $this->downloadStorage($remission->filename, 'remission');
    }

}
