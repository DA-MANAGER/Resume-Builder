<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Resume;
use App\User;
use Illuminate\Http\Request;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class ResumeController extends Controller
{
    /**
     * Generates a preview for the resume.
     *
     * @param  Request $request
     *
     * @return string
     */
    public function getPreview(Request $request) {
        $request->validate([
            'author_id' => 'exists:users,id',
            'data'      => 'required',
            'template'  => 'required|string',
            'title'     => 'required|string',
        ]);

        $author = null;

        if ($request->has('author_id')) {
            $author = User::findOrFail($request->input('author_id'));
        }

        $data     = json_decode($request->input('data'));
        $template = $request->input('template');
        $title    = $request->input('title');

        return (new Resume)->generatePreview([
                'author'       => $author,
                'data'         => $data,
                'template'     => $template,
                'title'        => $title,
            ]);
    }

    /**
     * Returns the list of resume templates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplates() {
        return response()->json(
            Resume::getUnignoredTemplates()
        );
    }
}
