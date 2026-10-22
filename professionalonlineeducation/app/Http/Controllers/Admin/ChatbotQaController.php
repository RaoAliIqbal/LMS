<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotQa;
use App\Support\AdminDbLoader;
use Illuminate\Http\Request;

class ChatbotQaController extends Controller
{
    public function index()
    {
        $payload = AdminDbLoader::modelList(ChatbotQa::class, 25, function ($q) {
            $q->orderBy('topic_key')->orderBy('sort_order')->orderBy('id');
        });
        $items = $payload['items'];
        $topicLabels = ChatbotQa::topicOptions();
        $view = view('admin.chatbot-qa.index', compact('items', 'topicLabels'))
            ->with('i', (request()->input('page', 1) - 1) * $items->perPage());

        if ($payload['error']) {
            return $view->with('error', $payload['error']);
        }

        return $view;
    }

    public function create()
    {
        $item = new ChatbotQa();
        $topicLabels = ChatbotQa::topicOptions();

        return view('admin.chatbot-qa.create', compact('item', 'topicLabels'));
    }

    public function store(Request $request)
    {
        $keys = implode(',', array_keys(ChatbotQa::topicOptions()));
        $data = $request->validate([
            'topic_key' => 'required|in:'.$keys,
            'label' => 'nullable|string|max:255',
            'tip_html' => 'required|string|max:20000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_active' => 'required|in:0,1',
        ]);
        $data['sort_order'] = isset($data['sort_order']) ? (int) $data['sort_order'] : 0;
        $data['is_active'] = (bool) (int) $data['is_active'];

        ChatbotQa::create($data);

        return redirect()->route('admin.chatbot-qas.index')
            ->with('success', 'Chatbot Q&A tip saved.');
    }

    public function edit($id)
    {
        $item = ChatbotQa::findOrFail($id);
        $topicLabels = ChatbotQa::topicOptions();

        return view('admin.chatbot-qa.edit', compact('item', 'topicLabels'));
    }

    public function update(Request $request, $id)
    {
        $item = ChatbotQa::findOrFail($id);
        $keys = implode(',', array_keys(ChatbotQa::topicOptions()));
        $data = $request->validate([
            'topic_key' => 'required|in:'.$keys,
            'label' => 'nullable|string|max:255',
            'tip_html' => 'required|string|max:20000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_active' => 'required|in:0,1',
        ]);
        $data['sort_order'] = isset($data['sort_order']) ? (int) $data['sort_order'] : 0;
        $data['is_active'] = (bool) (int) $data['is_active'];

        $item->update($data);

        return redirect()->route('admin.chatbot-qas.index')
            ->with('success', 'Chatbot Q&A tip updated.');
    }

    public function destroy($id)
    {
        ChatbotQa::where('id', $id)->delete();

        return redirect()->route('admin.chatbot-qas.index')
            ->with('success', 'Deleted.');
    }
}
