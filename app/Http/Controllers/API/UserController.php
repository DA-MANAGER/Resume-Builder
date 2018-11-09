<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class UserController extends Controller
{
    /**
     * Returns the list of authors matching the searched query.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAuthors(Request $request) {
        $request->validate([
            'term' => 'required|string'
        ]);

        $term = '%' . $request->input('term') . '%';

        $authors = User::where(function ($query) use ($term) {
            $query->where('name', 'like', $term)
                    ->orWhere('username', 'like', $term)
                    ->orWhere('email', 'like', $term);
        })
            ->get();

        return UserResource::collection($authors);
    }
}
