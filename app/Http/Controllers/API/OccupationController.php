<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OccupationResource;
use App\Http\Resources\ResponsibilityResource;
use App\Occupation;
use Illuminate\Http\Request;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class OccupationController extends Controller
{
    /**
     * Returns the list of occupations matching the searched query.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getOccupations(Request $request) {
        $request->validate([
            'term' => 'string'
        ]);

        $occupations = new Occupation;

        // We'll check whether the client has supplied any search query
        // for the occupations if they did then we'll return the
        // occupations matching that search query otherwise we'll return
        // all the existing occupations in the application.
        if ($request->has('term')) {
            $term = $request->input('term');
            $occupations = $occupations->where("name", "like", "%$term%")->get();
        } else {
            $occupations = $occupations->all();
        }

        return OccupationResource::collection($occupations);
    }

    /**
     * Returns the responsibilities of the supplied occupation id.
     *
     * @param  int $occupation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponsibilities($occupation_id) {
        $occupation = Occupation::findOrFail($occupation_id);

        return ResponsibilityResource::collection(
            $occupation->responsibilities
        );
    }

    /**
     * Stores the new occupation into the database.
     * 
     * @param  Request $request
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function storeOccupation(Request $request) {
        // 
    }

    /**
     * Stores the new responsibility into the database.
     * 
     * @param  Request $request
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function storeResponsibility(Request $request) {
        // 
    }

    /**
     * Updates the existing occupation.
     * 
     * @param  Request $request
     * @param  int $occupation_id
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function updateOccupation(Request $request, $occupation_id) {
        // 
    }

    /**
     * Updates the existing responsibility.
     * 
     * @param  Request $request
     * @param  int $responsibility_id
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function updateResponsibility(Request $request, $responsibility_id) {
        // 
    }
}
