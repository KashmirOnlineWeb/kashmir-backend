<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Meta;

class MetaController extends Controller
{
    /**
     * Display list of meta
     */
    public function index()
    {
        return view('meta.index');
    }

    /**
     * Create meta form.
     */
    public function create(Request $request): View
    {
        return view('meta.edit');
    }

    /**
     * Store meta form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                                'meta_title'       => 'required|string',
                                'meta_description' => 'required',
                                'keywords'         => 'required|string',
                                'status'           => 'required|digits_between:0,1'
                            ]);

            $response = Meta::create([
                                    'meta_title'       => $data['meta_title'],
                                    'meta_description' => $data['meta_description'],
                                    'keywords'         => $data['keywords'],
                                    'status'           => $data['status'],
                                ]);

            return Redirect::route('meta.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in meta store.');
        }
    }

    /**
     * Display the meta form.
     */
    public function edit(Request $request, $id): View
    {
        try {
            $request->merge(['id' => $id]);
            $data = $request->all();
            return view('meta.edit')->with('id', $data['id']);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in meta edit.');  
        }
    }

    /**
     * Update the meta information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $request->merge(['meta_id' => $id]);
            $data = $request->all();
            
            $request->validate([
                                'meta_id'          => 'required|integer|exists:metas,id',
                                'meta_title'       => 'required|string',
                                'meta_description' => 'required',
                                'keywords'         => 'required|string',
                                'status'           => 'required|digits_between:0,1'
                            ]);
            
            $response = Meta::where('id', $data['meta_id'])
                            ->update([
                                    'meta_title'       => $data['meta_title'],
                                    'meta_description' => $data['meta_description'],
                                    'keywords'         => $data['keywords'],
                                    'status'           => $data['status'],
                            ]);

            return Redirect::route('meta.edit',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in meta update.');
        }
    }

    /**
     * Delete the meta.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}