<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\DetailedUserProductResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\DetailedUserResource;
use App\Models\ProductUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('products')->get();
        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request)
    {
        $userData = $request->validated();
        $userProductData = [];

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($userData);

        if(isset($userData['products_id'])) {
            foreach ($userData['products_id'] as $product) {
                $userProductData[] = [
                    'user_id'    => $user['id'],
                    'product_id' => $product,
                ];
            }

            ProductUser::insert($userProductData);
        }


        return new DetailedUserResource($user);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return new DetailedUserProductResource($user);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $userData = $request->validated();

            if ($request->hasFile('avatar')) {
                Storage::disk('public')->delete($user->avatar);
                $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            if(isset($userData['products_id'])) {
                $user->products()->detach(); // Detach associated products

                foreach ($userData['products_id'] as $product) {
                    $userProductData[] = [
                        'user_id'    => $user['id'],
                        'product_id' => $product,
                    ];
                }

                ProductUser::insert($userProductData);
            } else {
                $user->products()->detach(); // Detach associated products
            }

            $user->update($userData);

            return new DetailedUserResource($user);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->products()->detach(); // Detach associated products

            if($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
