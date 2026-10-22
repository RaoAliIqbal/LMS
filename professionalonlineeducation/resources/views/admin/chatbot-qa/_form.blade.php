<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            <label for="topic_key">Topic (matches website support chatbot)</label>
            <select name="topic_key" id="topic_key" class="form-control @error('topic_key') is-invalid @enderror" required>
                @foreach($topicLabels as $key => $label)
                    <option value="{{ $key }}" {{ old('topic_key', $item->topic_key) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('topic_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="label">Label (optional — for your reference in this list)</label>
            <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror"
                   value="{{ old('label', $item->label) }}" maxlength="255" placeholder="e.g. Password reset tip">
            @error('label')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="tip_html">Answer / tip (HTML)</label>
            <textarea name="tip_html" id="tip_html" rows="6" class="form-control @error('tip_html') is-invalid @enderror"
                      required maxlength="20000" placeholder="Example: Try a &lt;strong&gt;private window&lt;/strong&gt; and log in again.">{{ old('tip_html', $item->tip_html) }}</textarea>
            <small class="text-muted">Shown as one bullet in the chatbot troubleshooting list for this topic. You may use &lt;strong&gt;, &lt;br&gt;, etc.</small>
            @error('tip_html')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="sort_order">Sort order</label>
            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="999999">
            @error('sort_order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="is_active">Active</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ (int) old('is_active', $item->is_active ?? 1) === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ (int) old('is_active', $item->is_active ?? 1) === 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.chatbot-qas.index') }}" class="btn btn-default">Cancel</a>
    </div>
</div>
