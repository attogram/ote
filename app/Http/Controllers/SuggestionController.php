<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\LexicalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Add authorization logic here later (only for admins/workers)
        $suggestions = Suggestion::with('user')->latest()->get();
        return view('suggestions.index', compact('suggestions'));
    }

    public function create(Request $request)
    {
        $model_type = $request->get('model_type');
        $model_id = $request->get('model_id');
        $type = $request->get('type');

        $model = null;
        if ($model_id) {
            $model = $model_type::find($model_id);
        }

        return view('suggestions.create', compact('model_type', 'model_id', 'type', 'model'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'in:create,update,delete'],
            'model_type' => ['required', 'string'],
            'model_id' => ['nullable', 'integer'],
            'data' => ['required', 'array'],
        ]);

        Suggestion::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'data' => $request->data,
        ]);

        return redirect()->route('home')->with('success', 'Your suggestion has been submitted for review.');
    }

    public function show(Suggestion $suggestion)
    {
        return view('suggestions.show', compact('suggestion'));
    }

    public function approve(Suggestion $suggestion)
    {
        // Authorization logic will be added later using middleware
        // $this->authorize('approve', $suggestion);

        $modelClass = $suggestion->model_type;
        $data = $suggestion->data;

        switch ($suggestion->type) {
            case 'create':
                $modelClass::create($data);
                break;
            case 'update':
                $model = $modelClass::find($suggestion->model_id);
                $model->update($data);
                break;
            case 'delete':
                $model = $modelClass::find($suggestion->model_id);
                $model->delete();
                break;
        }

        $suggestion->update(['status' => 'approved']);

        return redirect()->route('suggestions.index')->with('success', 'Suggestion approved.');
    }

    public function reject(Suggestion $suggestion)
    {
        // Authorization logic will be added later using middleware
        // $this->authorize('reject', $suggestion);

        $suggestion->update(['status' => 'rejected']);

        return redirect()->route('suggestions.index')->with('success', 'Suggestion rejected.');
    }
}
