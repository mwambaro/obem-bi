<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageView;
use App\Http\Controllers\ObemMainController;

class PageViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Prolog
        load_locale($request);
        seed_articles();
        $obem_open_graph_proto_locale = 'fr_FR';
        $obem_site_title = obem_site_title(__FUNCTION__);
        
        // Data
        $pages_analytics = null;

        if(!user_has_admin_role())
        {
            // Redirect
            return redirect(action([ObemMainController::class, 'home']));
        }
        
        $pages_analytics = get_pages_analytics();

        return view('page_views.analytics')
                ->with('site_title', $obem_site_title)
                ->with('obem_open_graph_proto_locale', $obem_open_graph_proto_locale)
                ->with('pages_analytics', $pages_analytics);


    } // index

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
