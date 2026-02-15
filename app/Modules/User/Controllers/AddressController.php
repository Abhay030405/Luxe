<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\Contracts\AddressRepositoryInterface;
use App\Modules\User\Requests\StoreAddressRequest;
use App\Modules\User\Requests\UpdateAddressRequest;
use App\Modules\User\Services\AddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function __construct(
        private readonly AddressService $addressService,
        private readonly AddressRepositoryInterface $addressRepository
    ) {}

    /**
     * Display a listing of the user's addresses.
     */
    public function index(): View
    {
        $addresses = $this->addressService->getAllAddressesForUser(auth()->id());

        return view('pages.user.addresses.index', [
            'addresses' => $addresses,
        ]);
    }

    /**
     * Show the form for creating a new address.
     */
    public function create(): View
    {
        $redirectTo = request()->query('redirect_to');

        return view('pages.user.addresses.create', [
            'redirectTo' => $redirectTo,
        ]);
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $this->addressService->createAddress(
            $request->user()->id,
            $request->validated()
        );

        // Check if there's a redirect_to parameter
        $redirectTo = $request->input('redirect_to');

        if ($redirectTo === 'checkout') {
            return redirect()->route('checkout.index')
                ->with('success', 'Address added successfully. Please complete your order.');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully.');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(int $id): View
    {
        $addressModel = $this->addressRepository->findByIdAndUser($id, auth()->id());

        abort_if(! $addressModel, 404);

        $this->authorize('update', $addressModel);

        $address = $this->addressService->getAddressForUser($id, auth()->id());

        return view('pages.user.addresses.edit', [
            'address' => $address,
        ]);
    }

    /**
     * Update the specified address in storage.
     */
    public function update(UpdateAddressRequest $request, int $id): RedirectResponse
    {
        $addressModel = $this->addressRepository->findByIdAndUser($id, $request->user()->id);

        abort_if(! $addressModel, 404);

        $this->authorize('update', $addressModel);

        $this->addressService->updateAddress(
            $id,
            $request->user()->id,
            $request->validated()
        );

        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $addressModel = $this->addressRepository->findByIdAndUser($id, auth()->id());

        abort_if(! $addressModel, 404);

        $this->authorize('delete', $addressModel);

        try {
            $this->addressService->deleteAddress($id, auth()->id());

            return redirect()->route('addresses.index')
                ->with('success', 'Address deleted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('addresses.index')
                ->withErrors($e->errors());
        }
    }

    /**
     * Set the specified address as default.
     */
    public function setDefault(int $id): RedirectResponse
    {
        $addressModel = $this->addressRepository->findByIdAndUser($id, auth()->id());

        abort_if(! $addressModel, 404);

        $this->authorize('setDefault', $addressModel);

        $this->addressService->setDefaultAddress($id, auth()->id());

        return redirect()->route('addresses.index')
            ->with('success', 'Default address updated successfully.');
    }
}
