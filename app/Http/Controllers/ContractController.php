<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContractController extends Controller
{
    private $contracts = [
        'sale_transfer'      => 'عقد بيع وتنازل',
        'land_sale_final'    => 'عقد بيع نهائي لقطعة أرض',
        'hajj_shop'          => 'عقد بيع وتنازل عن محل تجاري',
        'final_sale'         => 'عقد بيع نهائي',
        'apartment_initial'  => 'عقد بيع ابتدائي لشقة سكنية ',
        'car_sale'           => 'عقد بيع سيارة مع التزام بنقل الملكية',
        'apartment_final'    => 'عقد بيع نهائي لشقة سكنية',
        'land_sale'          => 'عقد بيع نهائي لقطعة أرض',
        'shared_share'       => 'عقد بيع حصة شائعة',
        'signature_validity' => 'صحة توقيع',
        'inheritance_sale'   => 'عقد بيع حصة إرث في منزل',
    ];

    public function index()
    {
        return view('contracts.index', ['contracts' => $this->contracts]);
    }

    public function show($type)
    {
        if (!array_key_exists($type, $this->contracts)) {
            abort(404);
        }
        return view('contracts.' . $type, ['title' => $this->contracts[$type]]);
    }
}