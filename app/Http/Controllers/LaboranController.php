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

class LaboranController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $query = request()->query();

        $laborans = Laboran::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return LaboranResource::collection($laborans);
    }

    public function show(Laboran $laboran)
    {
        $laboran->load([
            'user',
        ]);

        return $this->success(new LaboranResource($laboran), "Laboran retrieved successfully");
    }

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
                $directory = 'laborans/images';
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
                $directory = 'laborans/images';
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

    public function destroy(Laboran $laboran)
    {
        Gate::authorize('management');

        $laboran->delete();

        return $this->success(null, "Laboran deleted successfully");
    }
}
