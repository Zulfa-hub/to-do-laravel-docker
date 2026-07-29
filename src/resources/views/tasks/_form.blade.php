@csrf
<div>
    <label class="label-field" for="judul">Judul Tugas</label>
    <input class="input-field" type="text" name="judul" id="judul" value="{{ old('judul', $task->judul ?? '') }}" required autofocus>
</div>

<div>
    <label class="label-field" for="deskripsi">Deskripsi</label>
    <textarea class="input-field" name="deskripsi" id="deskripsi" rows="4">{{ old('deskripsi', $task->deskripsi ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="label-field" for="category_id">Kategori</label>
        <select class="input-field" name="category_id" id="category_id">
            <option value="">Tanpa Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $task->category_id ?? '') == $category->id)>
                    {{ $category->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label-field" for="priority">Prioritas</label>
        <select class="input-field" name="priority" id="priority" required>
            <option value="tinggi" @selected(old('priority', $task->priority ?? 'sedang') === 'tinggi')>&#128308; Tinggi</option>
            <option value="sedang" @selected(old('priority', $task->priority ?? 'sedang') === 'sedang')>&#128993; Sedang</option>
            <option value="rendah" @selected(old('priority', $task->priority ?? 'sedang') === 'rendah')>&#128994; Rendah</option>
        </select>
    </div>
</div>

<div>
    <label class="label-field" for="deadline">Deadline</label>
    <input class="input-field" type="datetime-local" name="deadline" id="deadline"
           value="{{ old('deadline', isset($task->deadline) ? $task->deadline?->format('Y-m-d\TH:i') : '') }}">
</div>

<div>
    <label class="label-field">Tag</label>
    <div class="flex flex-wrap gap-2">
        @forelse ($tags as $tag)
            @php
                $selectedTags = old('tags', isset($task) ? $task->tags->pluck('id')->toArray() : []);
            @endphp
            <label class="badge cursor-pointer border {{ in_array($tag->id, $selectedTags) ? 'bg-astra-500 text-white border-astra-500' : 'bg-white text-gray-600 border-astra-200' }}">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="hidden" @checked(in_array($tag->id, $selectedTags))
                       onclick="this.closest('label').classList.toggle('bg-astra-500'); this.closest('label').classList.toggle('text-white');">
                #{{ $tag->nama_tag }}
            </label>
        @empty
            <p class="text-xs text-gray-400">Belum ada tag. <a href="{{ route('tags.index') }}" class="text-astra-600 hover:underline">Tambah tag</a> terlebih dahulu.</p>
        @endforelse
    </div>
</div>
