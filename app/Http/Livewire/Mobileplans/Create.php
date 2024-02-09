<?php

namespace App\Http\Livewire\Mobileplans;

use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Forms\Components;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use App\Models\ServiceQualification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Integrations\APIHub\Requests\SuperloopLocationQualificationRequest;
use App\Models\Tenant;
use App\Models\User;
use Redirect;
use Closure;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use App\Http\Controllers\MobileplanController;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SimcardImport;
use App\Models\Reseller;
use Filament\Forms\Components\Radio;
use Auth;
use App\Models\MobilePlans;
use App\Models\VendorProduct;
use App\Models\ResellerWholesalePackage;
use App\Models\RetailPackage;
use App\Models\WholesalePackage;
use App\Models\RetailPackageOption;
use App\Models\WholesalePackageOption;
use Filament\Forms\Components\Repeater;

class Create extends Component implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    public $plan_name;
    public $plan_code;
    public $is_data_pool;
    public $is_prepaid;
    public $is_auto_topup;
    public $plan_type='wholesale';
    public $package_name;
    public $package_code;
    public $package_option;
    public $package_option_code;
    public $package_option_price;
    public $usage_type;
    public $reseller_id;
    public $reseller_ids;
    public $show_single_reseler_dropdown=false;
    public $show_multiple_reseler_dropdown=true;
    public $show_packes=true;


    public function mount(): void
    {
        // dd(Auth::user()->tenant_role);
    }

    public function onPlanTypeChange($state)
    {
        if($state != null && $state == 'wholesale'){
            $this->show_multiple_reseler_dropdown = true;
            $this->show_single_reseler_dropdown = false;
            $this->show_packes = true;
        }else if($state != null && $state == 'vendor'){
            $this->show_multiple_reseler_dropdown = false;
            $this->show_single_reseler_dropdown = true;
            $this->show_packes = false;
        }
        else{
            $this->show_multiple_reseler_dropdown = false;
            $this->show_single_reseler_dropdown = true;
            $this->show_packes = true;
        }
    }


    protected function getFormSchema(): array
    {
        return [
            Section::make('Add New  Mobile plan')
                // ->color("primary")
                // ->icon('ri-upload-cloud-line')
                // ->description('upload your SIM cards csv sheet here')
                // ->columns(2)

                ->schema([
                    Grid::make()
                    ->schema([
                        Forms\Components\Select::make('plan_type')
                        ->label("Plan Type")
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state) => $this->onPlanTypeChange($state))
                        ->default('wholesale')
                        ->options([
                            'vendor' => 'Vendor',
                            'wholesale' => 'Wholesale',
                            'retail' => 'Retail',
                        ]),

                    ]),
                    Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('plan_name')
                            ->required()
                            ->label("Vendor Product Name"),
                        Forms\Components\TextInput::make('plan_code')
                            ->required()
                            ->label("Vendor Plan ID"),
                    ]),
                    Grid::make(4)
                    ->schema([
                        Forms\Components\Toggle::make('is_data_pool')->label("Data Pool")->inline(false),
                        Forms\Components\Toggle::make('is_prepaid')->label("Prepaid")->inline(false),
                        Forms\Components\Toggle::make('is_auto_topup')->label("Auto Topup")->inline(false),

                    ]),
                    Grid::make()
                    ->schema([
                        Forms\Components\Select::make('reseller_id')
                        ->label("Reseller")
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->hidden(fn (Closure $get): bool => $get('show_single_reseler_dropdown') == false)
                        // ->afterStateUpdated(fn ($state) => $this->onResellerSelectChange($state))
                        ->options(function () {
                            return  Reseller::all()->pluck('reseller_name','reseller_id');;
                        }),
                        Forms\Components\Select::make('reseller_ids')
                        ->label("Reseller(s)")
                        ->multiple()
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->hidden(fn (Closure $get): bool => $get('show_multiple_reseler_dropdown') == false)
                        // ->afterStateUpdated(fn ($state) => $this->onResellerSelectChange($state))
                        ->options(function () {
                            return  Reseller::all()->pluck('reseller_name','reseller_id');;
                        }),
                    ]),


                    Grid::make()
                    ->hidden(fn (Closure $get): bool => $get('show_packes') == false)
                    ->schema([
                        Forms\Components\TextInput::make('package_name')
                            ->label("Package Name"),
                        Forms\Components\TextInput::make('package_code')
                            ->label("Package Code"),

                    ]),
                    Repeater::make('package_options')
                        ->grid(3)
                        ->hidden(fn (Closure $get): bool => $get('show_packes') == false)
                        ->schema([
                            Forms\Components\TextInput::make('package_option')
                                ->label("Package Option"),
                            Forms\Components\TextInput::make('package_option_code')
                                ->label("Package Option Code"),
                            Forms\Components\TextInput::make('package_option_price')
                                ->label("Price"),
                        ])
                ])

        ];
    }

    public function submit()
    {
        $data = $this->form->getState();
        $data['is_data_pool'] = $this->checkToggleData($data['is_data_pool']);
        $data['is_prepaid'] = $this->checkToggleData($data['is_prepaid']);
        $data['is_auto_topup'] = $this->checkToggleData($data['is_auto_topup']);
        // $data = $request->store($state);
        $vendor_products = new VendorProduct();
        $vendor_products->vendor_id = 1;
        $vendor_products->vendor_product_code = $data['plan_code'];
        $vendor_products->vendor_product_name = $data['plan_name'];
        $vendor_products->prepaid =  $data['is_prepaid'];
        $vendor_products->auto_top_up =  $data['is_auto_topup'];
        $vendor_products->datapool =  $data['is_data_pool'];
        $vendor_products->status =  'active';
        $vendor_products->save();

        if($data['plan_type'] === 'vendor'){
            Notification::make()
            ->title('Success')
            ->duration(20000)
            ->body('the package has been successfully added')
            ->status('success') // This sets the notification to be an error notification
            ->send();

        return to_route('mobileplans.index');
        }

        //get last vendor product id
        $vendor_product_id = VendorProduct::latest()->first()->id;


        if($data['plan_type'] === 'wholesale'){
            foreach($data['reseller_ids'] as $reseller_id){
                $reseller_wholesale_package = new WholesalePackage();
                $reseller_wholesale_package->wholesale_pakage_code = $data['plan_code'];
                $reseller_wholesale_package->wholesale_pakage_name = $data['plan_name'];
                $reseller_wholesale_package->reseller_id = $reseller_id;
                $reseller_wholesale_package->vendor_inventory_id = $vendor_product_id;
                $reseller_wholesale_package->datapool = $data['is_data_pool'];
                $reseller_wholesale_package->save();

                //get last wholesale package id
                $reseller_wholesale_package_id = WholesalePackage::latest()->first()->id;
                foreach($data['package_options'] as $option){
                    $package_option = new WholesalePackageOption();
                    $package_option->wholesale_pakage_id = $reseller_wholesale_package_id ;
                    $package_option->wholesale_pakage_code = $option['package_option_code'];
                    $package_option->wholesale_pakage_option_name = $option['package_option'];
                    $package_option->price = $option['package_option_price'];

                    $package_option->save();
                }

            }


        }else{
            $retail_package = new RetailPackage();
            $retail_package->retail_pakage_code = $data['plan_code'];
            $retail_package->retail_pakage_name = $data['plan_name'];
            $retail_package->reseller_id = $data['reseller_id'];
            $retail_package->vendor_inventory_id = $vendor_product_id;
            $retail_package->save();

            //get last retail package id
            $retail_package_id = RetailPackage::latest()->first()->id;

            $package_option = new RetailPackageOption();
            $package_option->retail_package_id = $retail_package_id ;
            $package_option->retail_pakage_code = $data['package_option_code'];
            $package_option->retail_pakage_option_name = $data['package_option'];
            $package_option->price = $data['package_option_price'];
            $package_option->save();
        }

        Notification::make()
            ->title('Success')
            ->duration(20000)
            ->body('the package has been successfully added')
            ->status('success') // This sets the notification to be an error notification
            ->send();

        return to_route('mobileplans.index');
    }


    private function checkToggleData($value){
        if($value == null){
            return 0;
        }
        return 1;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User updated')
            ->body('The user has been saved successfully.');
    }

    public function render()
    {
        return view('livewire.mobileplans.create');
    }
}
