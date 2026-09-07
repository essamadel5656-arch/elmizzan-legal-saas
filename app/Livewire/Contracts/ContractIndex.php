<?php

namespace App\Livewire\Contracts;

use Livewire\Component;

class ContractIndex extends Component
{
    public string $search = '';

    private array $allContracts = [
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

    public function render()
    {
        $filtered = collect($this->allContracts)->filter(function ($title) {
            return empty($this->search) || str_contains($title, $this->search);
        })->all();

        return view('livewire.contracts.contract-index', [
            'contracts' => $filtered,
        ])->title('المكتبة القانونية | ' . firm_name());
    }
}
