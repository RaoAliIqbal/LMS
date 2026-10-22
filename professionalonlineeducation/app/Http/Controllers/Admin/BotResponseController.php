<?php

namespace App\Http\Controllers\Admin;

use App\Models\BotResponse;
use App\Support\AdminDbLoader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/**
 * Class BotResponseController
 * @package App\Http\Controllers
 */
class BotResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payload = AdminDbLoader::modelList(BotResponse::class, 20);
        $botResponses = $payload['items'];
        $view = view('bot-response.index', compact('botResponses'))
            ->with('i', (request()->input('page', 1) - 1) * $botResponses->perPage());

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
        $botResponse = new BotResponse();
        return view('bot-response.create', compact('botResponse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        request()->validate(BotResponse::$rules);

        $botResponse = BotResponse::create($request->all());

        return redirect()->route('bot-responses.index')
            ->with('success', 'BotResponse created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $botResponse = BotResponse::find($id);

        return view('bot-response.show', compact('botResponse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $botResponse = BotResponse::find($id);

        return view('bot-response.edit', compact('botResponse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BotResponse $botResponse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BotResponse $botResponse)
    {
        request()->validate(BotResponse::$rules);

        $botResponse->update($request->all());

        return redirect()->route('bot-responses.index')
            ->with('success', 'BotResponse updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $botResponse = BotResponse::find($id)->delete();

        return redirect()->route('bot-responses.index')
            ->with('success', 'BotResponse deleted successfully');
    }
}
