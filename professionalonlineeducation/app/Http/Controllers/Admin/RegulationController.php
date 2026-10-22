<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Regulation;
use App\Support\AdminDbLoader;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/**
 * Class RegulationController
 * @package App\Http\Controllers
 */
class RegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payload = AdminDbLoader::modelList(Regulation::class, 20);
        $regulations = $payload['items'];
        $view = view('regulation.index', compact('regulations'))
            ->with('i', (request()->input('page', 1) - 1) * $regulations->perPage());

        if ($payload['error']) {
            return $view->with('error', $payload['error']);
        }

        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regulation = new Regulation();
        $states = State::all();
        $categories = Category::orderBy('sort_by','ASC')->get();
        return view('regulation.create', compact('regulation','states','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Regulation::$rules);

        $regulation = Regulation::create($request->all());

        return redirect()->route('regulations.index')
            ->with('success', 'Regulation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regulation = Regulation::find($id);

        return view('regulation.show', compact('regulation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regulation = Regulation::find($id);
        $states = State::all();
        $categories = Category::orderBy('sort_by','ASC')->get();
        return view('regulation.edit', compact('regulation','states','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Regulation $regulation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Regulation $regulation)
    {
        request()->validate(Regulation::$rules);

        $regulation->update($request->all());

        return redirect()->route('regulations.index')
            ->with('success', 'Regulation updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $regulation = Regulation::find($id)->delete();

        return redirect()->route('regulations.index')
            ->with('success', 'Regulation deleted successfully');
    }
}
