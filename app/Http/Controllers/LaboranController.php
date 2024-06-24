<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaboranStoreRequest;
use App\Http\Requests\LaboranUpdateRequest;
use App\Http\Resources\LaboranResource;
use App\Models\Laboran;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @group Laboran
 *
 * APIs for managing laborans
 *
 * @authenticated
 */
class LaboranController extends Controller
{
    use HttpResponses;

    /**
     * Get laborans
     *
     * Get a list of laborans
     *
     * @queryParam name string The name of the laboran. Example: laboran
     * @queryParam nip string The nip of the laboran. Example: 123456789
     * @queryParam min_date_of_birth date The minimum date of birth of the laboran. Example: 2000-01-01
     * @queryParam max_date_of_birth date The maximum date of birth of the laboran. Example: 2000-01-01
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of laborans to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: name
     * @queryParam sort_direction string The direction to sort. Example: asc
     */
    public function index()
    {
        $query = request()->query();

        $laborans = Laboran::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return LaboranResource::collection($laborans);
    }


    /**
     * Get laboran details
     *
     * @urlParam laboran required The ID of the laboran. Example: 1
     */
    public function show(Laboran $laboran)
    {
        $laboran->load([
            'user',
        ]);

        return $this->success(new LaboranResource($laboran), "Laboran retrieved successfully");
    }

    /**
     * Add laboran
     *
     * Add a new laboran
     *
     * @bodyParam name string required The name of the laboran. Example: laboran
     * @bodyParam email string required The email of the laboran. Example: laboran@mail.com
     * @bodyParam password string required The password of the laboran. Example: password
     * @bodyParam phone string required The phone of the laboran. Example: 081234567890
     * @bodyParam nip string required The nip of the laboran. Example: 123456789
     * @bodyParam date_of_birth date The date of birth of the laboran. Example: 2000-01-01
     * @bodyParam profile_image file The profile image of the laboran.
     *
     * @subgroup Management
     */
    public function store(LaboranStoreRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role_id' => 4
            ]);

            Laboran::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'name' => $validated['name'],
            ]);

            if (isset($validated['profile_image'])) {
                $image = $validated['profile_image'];
                $directory = 'laborans/images/';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $imageUrl = url('/storage/' . $directory . $image->hashName());

                $user->update([
                    'profile_image' => $imageUrl
                ]);
            }
        });

        $laboran = Laboran::latest()->first();

        return $this->success(new LaboranResource($laboran), "Laboran created successfully");
    }

    /**
     * Update laboran
     *
     * @urlParam laboran required The ID of the laboran. Example: 1
     *
     * @bodyParam name string The name of the laboran. Example: laboran
     * @bodyParam email string The email of the laboran. Example: laboran@mail.com
     * @bodyParam phone string The phone of the laboran. Example: 081234567890
     * @bodyParam nip string The nip of the laboran. Example: 123456789
     * @bodyParam date_of_birth date The date of birth of the laboran. Example: 2000-01-01
     * @bodyParam profile_image file The profile image of the laboran.
     *
     * @subgroup Management
     */
    public function update(LaboranUpdateRequest $request, Laboran $laboran)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $laboran) {
            $laboran->user->update([
                'email' => $validated['email'] ?? $laboran->user->email,
                'phone' => $validated['phone'] ?? $laboran->user->phone,
            ]);

            $laboran->update([
                'nip' => $validated['nip'] ?? $laboran->nip,
                'name' => $validated['name'] ?? $laboran->name,
                'date_of_birth' => $validated['date_of_birth'] ?? $laboran->date_of_birth,
            ]);

            if (isset($validated['profile_image'])) {
                $image = $validated['profile_image'];
                $directory = 'laborans/images/';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $imageUrl = url('/storage/' . $directory . $image->hashName());

                Storage::disk('local')->delete('public/' . $directory . basename($laboran->user->profile_image));

                $laboran->user->update([
                    'profile_image' => $imageUrl
                ]);
            }
        });

        return $this->success(new LaboranResource($laboran), "Laboran updated successfully");
    }

    /**
     * Delete laboran
     *
     * @urlParam laboran required The ID of the laboran. Example: 1
     *
     * @subgroup Management
     */
    public function destroy(Laboran $laboran)
    {
        Gate::authorize('management');

        $laboran->delete();

        return $this->success(null, "Laboran deleted successfully");
    }
}
