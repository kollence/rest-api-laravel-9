<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Filters\V1\CustomersFilter;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $filter = new CustomersFilter();
        $queryItemsArr = $filter->transform($request); //[['column','operator', 'value']]
        // dd($queryItemsArr);
        $customers = Customer::where($queryItemsArr);
        // if query string has invoices = true then add relationship field with data
        if($request->query('invoices')){
            $customers = $customers->with('invoices');
        }

        return new CustomerCollection($customers->paginate(10)->appends($request->query()));
    }


    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    public function show(Customer $customer)
    {
        // if query string has invoices = true then add relationship field with data
        if(request()->query('invoices')){
            return new CustomerResource($customer->loadMissing('invoices'));
        }
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        if ($this->user()->tokenCan('delete')) {
            return Customer::destroy($customer->id);
        }
    }
}
