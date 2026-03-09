@extends('layouts.student')

@section('title', 'Éditer « ' . $project->title . ' »')
@section('page-title', 'Éditer le projet')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="projectEditor()" x-on:show-toast.window="showToast($event.detail.message, $event.detail.type || 'success')">

    {{-- Toast notification --}}
    <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
         :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
         class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-[100] flex items-center gap-3 text-sm font-medium" x-cloak>
        <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span x-text="toast.message"></span>
    </div>

    {{-- Project header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Dernière modification {{ $project->updated_at->diffForHumans() }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.projects.show', $project) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Aperçu
            </a>
        </div>
    </div>

    {{-- General info card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="font-semibold text-gray-800">Informations générales</h3>
        </div>
        <form @submit.prevent="saveGeneralInfo" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Titre du projet <span class="text-red-400">*</span></label>
                    <input type="text" x-model="generalInfo.title" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie</label>
                    <select x-model="generalInfo.category_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                        <option value="">— Sans catégorie —</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea id="description" rows="8"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="flex justify-end mt-5 pt-5 border-t border-gray-100">
                <button type="submit" :disabled="saving" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors">
                    <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="saving ? 'Enregistrement...' : 'Enregistrer'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- Data Sections --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <h3 class="font-semibold text-gray-800">Sections de données</h3>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $project->data->count() }}</span>
            </div>
            <button @click="showAddSection = true" type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter
            </button>
        </div>

        <div class="p-6 space-y-4">
            @forelse($project->data as $section)
            <div class="border border-gray-200 rounded-xl overflow-hidden" data-section-id="{{ $section->id }}" x-data="sectionManager({{ $section->id }}, '{{ $section->type }}', {{ json_encode($section->content) }}, '{{ addslashes($section->name) }}')">
                {{-- Section header --}}
                <div class="flex items-center justify-between px-5 py-3 bg-gray-50/80 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm"
                             :class="{'bg-emerald-100 text-emerald-600': type==='value', 'bg-blue-100 text-blue-600': type==='image', 'bg-amber-100 text-amber-600': type==='table', 'bg-purple-100 text-purple-600': type==='chart'}">
                            <template x-if="type==='value'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></template>
                            <template x-if="type==='image'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></template>
                            <template x-if="type==='table'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></template>
                            <template x-if="type==='chart'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></template>
                        </div>
                        <div>
                            <template x-if="!editingName">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-800 text-sm" x-text="sectionName"></span>
                                    <button @click="editingName = true; $nextTick(() => $refs.nameInput.focus())" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                </div>
                            </template>
                            <template x-if="editingName">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-ref="nameInput" x-model="sectionName" @keydown.enter="saveName()" @keydown.escape="editingName = false"
                                           class="px-2 py-1 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-48">
                                    <button @click="saveName()" class="text-green-600 hover:text-green-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button @click="editingName = false" class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="expanded = !expanded" class="p-2 rounded-lg hover:bg-gray-200 text-gray-500 transition-colors">
                            <svg class="w-4 h-4 transition-transform" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <button @click="deleteSection()" class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Section body --}}
                <div x-show="expanded" x-collapse class="p-5">
                    @if($section->type === 'value')
                    <div class="space-y-4">
                        <textarea x-model="content.value" rows="5" placeholder="Entrez votre contenu ici..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-y transition-shadow"></textarea>
                        <div class="flex justify-end">
                            <button @click="saveContent()" :disabled="sectionSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors">
                                <svg x-show="sectionSaving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Enregistrer
                            </button>
                        </div>
                    </div>

                    @elseif($section->type === 'image')
                    <div class="space-y-4">
                        @if($section->files->count())
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($section->files as $file)
                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                                <img src="{{ Storage::url($file->path) }}" alt="{{ $file->original_name }}"
                                     class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                     @click="$dispatch('open-lightbox', '{{ Storage::url($file->path) }}')">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                        <button @click="$dispatch('open-lightbox', '{{ Storage::url($file->path) }}')" class="p-2 bg-white/90 rounded-full shadow-sm hover:bg-white transition-colors">
                                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        </button>
                                        <button @click="deleteFile({{ $file->id }}, {{ $section->id }})" class="p-2 bg-red-500/90 rounded-full shadow-sm hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-6 text-gray-400 text-sm">Aucune image dans cette section.</div>
                        @endif
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-green-400 transition-colors">
                            <form @submit.prevent="uploadImages($event, {{ $section->id }})" enctype="multipart/form-data">
                                <label class="flex items-center gap-3 cursor-pointer w-full">
                                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <span class="text-green-600 font-medium">Choisir des images</span> ou glisser-déposer
                                    </div>
                                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="$el.closest('form').requestSubmit()">
                                </label>
                            </form>
                        </div>
                    </div>

                    @elseif($section->type === 'table')
                    <div x-data="tableManager(content)" class="space-y-4">
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <template x-for="(col, ci) in columns" :key="'th-'+ci">
                                            <th class="px-3 py-2 border-b border-r border-gray-200">
                                                <div class="flex items-center gap-1">
                                                    <input type="text" x-model="columns[ci]" placeholder="En-tête"
                                                           class="w-full bg-transparent text-xs font-semibold text-gray-700 focus:outline-none focus:bg-white focus:px-2 focus:py-1 focus:rounded-lg focus:ring-1 focus:ring-green-500 transition-all">
                                                    <button @click="removeColumn(ci)" class="text-gray-300 hover:text-red-500 transition-colors flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        <th class="px-2 py-2 border-b border-gray-200 w-12">
                                            <button @click="addColumn()" class="p-1 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors w-full flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, ri) in rows" :key="'tr-'+ri">
                                        <tr class="hover:bg-gray-50/50">
                                            <template x-for="(cell, ci) in row" :key="'td-'+ri+'-'+ci">
                                                <td class="px-1 py-1 border-b border-r border-gray-200">
                                                    <input type="text" x-model="rows[ri][ci]" placeholder="—"
                                                           class="w-full px-2 py-1.5 text-xs text-gray-700 bg-transparent focus:outline-none focus:bg-white focus:rounded-lg focus:ring-1 focus:ring-green-500 transition-all">
                                                </td>
                                            </template>
                                            <td class="px-2 py-1 border-b border-gray-200 text-center">
                                                <button @click="removeRow(ri)" class="text-gray-300 hover:text-red-500 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between">
                            <button @click="addRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-medium transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Ajouter une ligne
                            </button>
                            <button @click="saveTable()" class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Enregistrer
                            </button>
                        </div>
                    </div>

                    @elseif($section->type === 'chart')
                    <div x-data="chartManager(content, {{ $section->id }})" x-init="initChart()" class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Type de graphique</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <template x-for="ct in chartTypes" :key="ct.value">
                                            <button type="button" @click="chartType = ct.value; updateChart()"
                                                    :class="chartType === ct.value ? 'ring-2 ring-green-500 bg-green-50 border-green-200' : 'bg-gray-50 hover:bg-gray-100 border-gray-200'"
                                                    class="p-2 rounded-xl border text-center transition-all">
                                                <div class="text-lg" x-text="ct.icon"></div>
                                                <div class="text-xs text-gray-600 mt-0.5" x-text="ct.label"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Étiquettes <span class="text-gray-400 font-normal">(séparées par virgules)</span></label>
                                    <input type="text" x-model="labelsStr" @input="updateChart()" placeholder="Ex : Jan, Fév, Mar, Avr"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-medium text-gray-600">Séries de données</label>
                                        <button type="button" @click="addDataset()" class="inline-flex items-center gap-1 text-xs text-green-600 hover:text-green-700 font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Ajouter
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(ds, di) in datasets" :key="'ds-'+di">
                                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <input type="text" x-model="ds.label" @input="updateChart()" placeholder="Nom de la série"
                                                           class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                                    <button type="button" @click="removeDataset(di)" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                                <input type="text" x-model="ds.dataStr" @input="parseDataset(di)" @blur="parseDataset(di)" placeholder="Valeurs : 10, 20, 30, 40"
                                                       class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-center min-h-[260px]">
                                <canvas :id="'chart-preview-' + sectionId" class="max-w-full"></canvas>
                            </div>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button @click="saveChart()" class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-gray-500 text-sm">Aucune section de données</p>
                <p class="text-gray-400 text-xs mt-1">Cliquez sur « Ajouter » pour créer votre première section</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Attachments --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            <h3 class="font-semibold text-gray-800">Fichiers joints</h3>
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">{{ $project->attachments->count() }}</span>
        </div>
        <div class="p-6">
            @if($project->attachments->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-5">
                @foreach($project->attachments as $attachment)
                <div class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50" data-attachment-id="{{ $attachment->id }}">
                    @if($attachment->isImage())
                    <div class="aspect-square">
                        <img src="{{ Storage::url($attachment->path) }}" alt="{{ $attachment->original_name }}"
                             class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                             @click="$dispatch('open-lightbox', '{{ Storage::url($attachment->path) }}')">
                    </div>
                    @elseif($attachment->isVideo())
                    <div class="aspect-square flex items-center justify-center bg-gray-900 text-white">
                        <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    @else
                    <div class="aspect-square flex flex-col items-center justify-center gap-2 p-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs text-gray-500 text-center truncate max-w-full">{{ $attachment->original_name }}</span>
                    </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white truncate">{{ Str::limit($attachment->original_name, 18) }}</span>
                            <button @click="deleteAttachment({{ $attachment->id }})" class="p-1 bg-red-500 hover:bg-red-600 text-white rounded-full transition-colors flex-shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-green-400 transition-colors">
                <form @submit.prevent="uploadAttachment($event)" enctype="multipart/form-data">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="text-green-600 font-medium">Choisir un fichier</span> — image, vidéo ou document
                        </div>
                        <input type="file" name="attachment" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden" @change="$el.closest('form').requestSubmit()">
                    </label>
                </form>
            </div>
        </div>
    </div>

    {{-- Add section modal --}}
    <div x-show="showAddSection" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="showAddSection = false" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @click.stop
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Nouvelle section</h3>
                <button @click="showAddSection = false" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <template x-for="t in sectionTypes" :key="t.value">
                        <button type="button" @click="newSection.type = t.value"
                                :class="newSection.type === t.value ? 'ring-2 ring-green-500 bg-green-50 border-green-200' : 'bg-gray-50 hover:bg-gray-100 border-gray-200'"
                                class="p-3 rounded-xl text-center transition-all border">
                            <div class="text-2xl mb-1" x-text="t.icon"></div>
                            <div class="text-xs font-medium text-gray-700" x-text="t.label"></div>
                        </button>
                    </template>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Nom de la section <span class="text-red-400">*</span></label>
                    <input type="text" x-model="newSection.name" placeholder="Ex : Résultats d'analyse"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-shadow">
                </div>
                <template x-if="newSection.type === 'value'">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Contenu</label>
                        <textarea x-model="newSection.value" rows="3" placeholder="Entrez le contenu..."
                                  class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none transition-shadow"></textarea>
                    </div>
                </template>
                <template x-if="newSection.type === 'table'">
                    <div x-data="newTableEditor()">
                        <div class="overflow-x-auto rounded-xl border border-gray-200 mb-3">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <template x-for="(col, ci) in columns" :key="'nc-'+ci">
                                            <th class="px-2 py-2 border-b border-r border-gray-200">
                                                <div class="flex items-center gap-1">
                                                    <input type="text" x-model="columns[ci]" placeholder="Colonne" class="w-full bg-transparent text-xs font-semibold focus:outline-none">
                                                    <button @click="removeColumn(ci)" class="text-gray-300 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                </div>
                                            </th>
                                        </template>
                                        <th class="px-2 py-2 border-b w-8"><button @click="addColumn()" class="text-green-600 hover:text-green-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, ri) in rows" :key="'nr-'+ri">
                                        <tr>
                                            <template x-for="(cell, ci) in row" :key="'nrc-'+ri+'-'+ci">
                                                <td class="px-1 py-1 border-b border-r border-gray-200"><input type="text" x-model="rows[ri][ci]" placeholder="—" class="w-full px-2 py-1 text-xs focus:outline-none"></td>
                                            </template>
                                            <td class="px-2 py-1 border-b text-center"><button @click="removeRow(ri)" class="text-gray-300 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <button @click="addRow()" class="inline-flex items-center gap-1 text-xs text-gray-600 hover:text-gray-800 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ligne
                        </button>
                        <input type="hidden" x-ref="tableColumnsHidden" :value="JSON.stringify(columns)">
                        <input type="hidden" x-ref="tableRowsHidden" :value="JSON.stringify(rows)">
                    </div>
                </template>
                <template x-if="newSection.type === 'chart'">
                    <div class="space-y-3">
                        <select x-model="newSection.chartType" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="bar">Barres</option><option value="line">Lignes</option><option value="pie">Secteurs</option>
                            <option value="doughnut">Anneau</option><option value="polarArea">Aire polaire</option><option value="radar">Radar</option>
                        </select>
                        <input type="text" x-model="newSection.labels" placeholder="Étiquettes : Jan, Fév, Mar" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <input type="text" x-model="newSection.datasetLabel" placeholder="Nom de la série" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <input type="text" x-model="newSection.datasetData" placeholder="Données : 10, 20, 30" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </template>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                <button @click="submitNewSection()" :disabled="addingSectionLoading" class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl text-sm font-medium transition-colors">
                    <span x-text="addingSectionLoading ? 'Ajout en cours...' : 'Ajouter la section'"></span>
                </button>
                <button @click="showAddSection = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">Annuler</button>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div x-data="{ lightboxUrl: '' }" @open-lightbox.window="lightboxUrl = $event.detail" x-show="lightboxUrl" x-cloak
         class="fixed inset-0 bg-black/90 flex items-center justify-center z-[200] p-4 cursor-pointer" @click="lightboxUrl = ''" @keydown.escape.window="lightboxUrl = ''">
        <button class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="lightboxUrl" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const PROJECT_ID = {{ $project->id }};
const ROUTES = {
    update: '{{ route("student.projects.update", $project) }}',
    dataStore: '{{ route("student.projects.data.store", $project) }}',
    dataUpdate: (id) => `/student/projects/${PROJECT_ID}/data/${id}`,
    dataDestroy: (id) => `/student/projects/${PROJECT_ID}/data/${id}`,
    fileDestroy: (sId, fId) => `/student/projects/${PROJECT_ID}/data/${sId}/files/${fId}`,
    attachmentStore: '{{ route("student.projects.attachments.store", $project) }}',
    attachmentDestroy: (id) => `/student/projects/${PROJECT_ID}/attachments/${id}`,
};

async function ajaxRequest(url, method, data = null, isFormData = false) {
    const opts = { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } };
    if (data) {
        if (isFormData) { opts.body = data; } else { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(data); }
    }
    const resp = await fetch(url, opts);
    if (!resp.ok) { const err = await resp.json().catch(() => ({})); throw new Error(err.message || 'Erreur serveur'); }
    return resp;
}

let easyMDE;
document.addEventListener('DOMContentLoaded', () => {
    easyMDE = new EasyMDE({
        element: document.getElementById('description'),
        spellChecker: false,
        status: false,
        placeholder: 'Rédigez votre description en Markdown...',
        toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'guide'],
    });
});

function projectEditor() {
    return {
        saving: false,
        showAddSection: false,
        addingSectionLoading: false,
        toast: { show: false, message: '', type: 'success' },
        generalInfo: { title: @json($project->title), category_id: @json($project->category_id ?? '') },
        newSection: { type: 'value', name: '', value: '', chartType: 'bar', labels: '', datasetLabel: '', datasetData: '' },
        sectionTypes: [
            { value: 'value', label: 'Texte', icon: '📝' },
            { value: 'image', label: 'Images', icon: '🖼️' },
            { value: 'table', label: 'Tableau', icon: '📊' },
            { value: 'chart', label: 'Graphique', icon: '📈' },
        ],
        showToast(msg, type = 'success') { this.toast = { show: true, message: msg, type }; setTimeout(() => this.toast.show = false, 3000); },
        async saveGeneralInfo() {
            this.saving = true;
            try {
                const fd = new FormData();
                fd.append('_method', 'PUT');
                fd.append('title', this.generalInfo.title);
                fd.append('category_id', this.generalInfo.category_id);
                fd.append('description', easyMDE ? easyMDE.value() : document.getElementById('description').value);
                await ajaxRequest(ROUTES.update, 'POST', fd, true);
                this.showToast('Informations enregistrées');
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.saving = false; }
        },
        async submitNewSection() {
            if (!this.newSection.name.trim()) { this.showToast('Veuillez saisir un nom', 'error'); return; }
            this.addingSectionLoading = true;
            try {
                const fd = new FormData();
                fd.append('name', this.newSection.name);
                fd.append('type', this.newSection.type);
                if (this.newSection.type === 'value') fd.append('value', this.newSection.value);
                else if (this.newSection.type === 'table') {
                    const c = document.querySelector('[x-ref=tableColumnsHidden]'), r = document.querySelector('[x-ref=tableRowsHidden]');
                    fd.append('table_columns', c ? c.value : '["Colonne 1","Colonne 2"]');
                    fd.append('table_rows', r ? r.value : '[["",""]]');
                } else if (this.newSection.type === 'chart') {
                    fd.append('chart_type', this.newSection.chartType);
                    fd.append('labels', this.newSection.labels);
                    fd.append('dataset_label', this.newSection.datasetLabel);
                    fd.append('dataset_data', this.newSection.datasetData);
                }
                await ajaxRequest(ROUTES.dataStore, 'POST', fd, true);
                this.showToast('Section ajoutée');
                this.showAddSection = false;
                this.newSection = { type: 'value', name: '', value: '', chartType: 'bar', labels: '', datasetLabel: '', datasetData: '' };
                setTimeout(() => location.reload(), 600);
            } catch (e) { this.showToast(e.message, 'error'); } finally { this.addingSectionLoading = false; }
        },
        async deleteAttachment(id) {
            if (!confirm('Supprimer ce fichier ?')) return;
            try {
                const fd = new FormData(); fd.append('_method', 'DELETE');
                await ajaxRequest(ROUTES.attachmentDestroy(id), 'POST', fd, true);
                this.showToast('Fichier supprimé');
                document.querySelector(`[data-attachment-id="${id}"]`)?.remove();
            } catch (e) { this.showToast(e.message, 'error'); }
        },
        async uploadAttachment(e) {
            const input = e.target.querySelector('input[type=file]');
            if (!input.files.length) return;
            const fd = new FormData(); fd.append('attachment', input.files[0]);
            try { await ajaxRequest(ROUTES.attachmentStore, 'POST', fd, true); this.showToast('Fichier ajouté'); setTimeout(() => location.reload(), 600); }
            catch (e2) { this.showToast(e2.message, 'error'); }
        },
    }
}

function sectionManager(id, type, content, name) {
    return {
        sectionId: id, type, content: content || {}, sectionName: name,
        expanded: true, editingName: false, sectionSaving: false,
        async saveName() {
            this.editingName = false; this.sectionSaving = true;
            try {
                const fd = new FormData(); fd.append('_method', 'PUT'); fd.append('name', this.sectionName); fd.append('type', this.type);
                if (this.type === 'value') fd.append('value', this.content.value || '');
                if (this.type === 'table') { fd.append('table_columns', JSON.stringify(this.content.columns || [])); fd.append('table_rows', JSON.stringify(this.content.rows || [])); }
                if (this.type === 'chart') { fd.append('chart_type', this.content.chart_type || 'bar'); fd.append('labels', JSON.stringify(this.content.labels || [])); fd.append('datasets', JSON.stringify(this.content.datasets || [])); }
                await ajaxRequest(ROUTES.dataUpdate(this.sectionId), 'POST', fd, true);
                this.$dispatch('show-toast', {message: 'Nom mis à jour'});
            } catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); } finally { this.sectionSaving = false; }
        },
        async saveContent() {
            this.sectionSaving = true;
            try {
                const fd = new FormData(); fd.append('_method', 'PUT'); fd.append('name', this.sectionName); fd.append('type', this.type); fd.append('value', this.content.value || '');
                await ajaxRequest(ROUTES.dataUpdate(this.sectionId), 'POST', fd, true);
                this.$dispatch('show-toast', {message: 'Section enregistrée'});
            } catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); } finally { this.sectionSaving = false; }
        },
        async deleteSection() {
            if (!confirm('Supprimer cette section ?')) return;
            try { const fd = new FormData(); fd.append('_method', 'DELETE'); await ajaxRequest(ROUTES.dataDestroy(this.sectionId), 'POST', fd, true); this.$dispatch('show-toast', {message: 'Section supprimée'}); document.querySelector(`[data-section-id="${this.sectionId}"]`)?.remove(); }
            catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); }
        },
        async uploadImages(e, sectionId) {
            const input = e.target.querySelector('input[type=file]'); if (!input.files.length) return;
            const fd = new FormData(); fd.append('_method', 'PUT'); fd.append('name', this.sectionName); fd.append('type', 'image');
            for (let i = 0; i < input.files.length; i++) fd.append('images[]', input.files[i]);
            try { await ajaxRequest(ROUTES.dataUpdate(sectionId), 'POST', fd, true); this.$dispatch('show-toast', {message: 'Images ajoutées'}); setTimeout(() => location.reload(), 600); }
            catch (e2) { this.$dispatch('show-toast', {message: e2.message, type: 'error'}); }
        },
        async deleteFile(fileId, sectionId) {
            if (!confirm('Supprimer cette image ?')) return;
            try { const fd = new FormData(); fd.append('_method', 'DELETE'); await ajaxRequest(ROUTES.fileDestroy(sectionId, fileId), 'POST', fd, true); this.$dispatch('show-toast', {message: 'Image supprimée'}); this.$el.closest('.group').remove(); }
            catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); }
        },
    }
}

function tableManager(data) {
    return {
        columns: data?.columns ? [...data.columns] : ['Colonne 1'],
        rows: data?.rows ? data.rows.map(r => [...r]) : [['']],
        addColumn() { this.columns.push('Colonne ' + (this.columns.length + 1)); this.rows = this.rows.map(r => [...r, '']); },
        removeColumn(i) { if (this.columns.length <= 1) return; this.columns.splice(i, 1); this.rows = this.rows.map(r => { r.splice(i, 1); return r; }); },
        addRow() { this.rows.push(this.columns.map(() => '')); },
        removeRow(i) { if (this.rows.length <= 1) return; this.rows.splice(i, 1); },
        async saveTable() {
            const p = this.$el.closest('[x-data*="sectionManager"]');
            const sm = p ? Alpine.$data(p) : null; if (!sm) return;
            sm.sectionSaving = true;
            try {
                const fd = new FormData(); fd.append('_method', 'PUT'); fd.append('name', sm.sectionName); fd.append('type', 'table');
                fd.append('table_columns', JSON.stringify(this.columns)); fd.append('table_rows', JSON.stringify(this.rows));
                await ajaxRequest(ROUTES.dataUpdate(sm.sectionId), 'POST', fd, true);
                this.$dispatch('show-toast', {message: 'Tableau enregistré'});
            } catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); } finally { sm.sectionSaving = false; }
        }
    }
}

function newTableEditor() {
    return {
        columns: ['Colonne 1', 'Colonne 2'], rows: [['', '']],
        addColumn() { this.columns.push('Colonne ' + (this.columns.length + 1)); this.rows = this.rows.map(r => [...r, '']); },
        removeColumn(i) { if (this.columns.length <= 1) return; this.columns.splice(i, 1); this.rows = this.rows.map(r => { r.splice(i, 1); return r; }); },
        addRow() { this.rows.push(this.columns.map(() => '')); },
        removeRow(i) { if (this.rows.length <= 1) return; this.rows.splice(i, 1); },
    }
}

function chartManager(data, sectionId) {
    const colors = ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'];
    let chartInstance = null;
    return {
        sectionId,
        chartType: data?.chart_type || 'bar',
        labelsStr: data?.labels ? data.labels.join(', ') : '',
        labels: data?.labels ? [...data.labels] : [],
        datasets: data?.datasets ? JSON.parse(JSON.stringify(data.datasets)).map(d => ({...d, dataStr: (d.data||[]).join(', ')})) : [{ label: 'Série 1', data: [], dataStr: '' }],
        chartTypes: [
            { value: 'bar', label: 'Barres', icon: '📊' },
            { value: 'line', label: 'Lignes', icon: '📈' },
            { value: 'pie', label: 'Secteurs', icon: '🥧' },
            { value: 'doughnut', label: 'Anneau', icon: '🍩' },
            { value: 'polarArea', label: 'Polaire', icon: '🎯' },
            { value: 'radar', label: 'Radar', icon: '🕸️' },
        ],
        addDataset() { this.datasets.push({ label: 'Série '+(this.datasets.length+1), data: [], dataStr: '' }); this.updateChart(); },
        removeDataset(i) { if (this.datasets.length <= 1) return; this.datasets.splice(i, 1); this.updateChart(); },
        parseDataset(i) { this.datasets[i].data = this.datasets[i].dataStr.split(',').map(v => parseFloat(v.trim())).filter(v => !isNaN(v)); this.updateChart(); },
        updateChart() {
            this.labels = this.labelsStr.split(',').map(l => l.trim()).filter(Boolean);
            const isC = ['pie','doughnut','polarArea'].includes(this.chartType);
            const ds = this.datasets.map((d, i) => ({ label: d.label, data: d.data, backgroundColor: isC ? this.labels.map((_,j) => colors[j%colors.length]) : colors[i%colors.length]+'40', borderColor: isC ? colors : colors[i%colors.length], borderWidth: 2, tension: 0.3 }));
            if (chartInstance) chartInstance.destroy();
            const c = document.getElementById('chart-preview-'+this.sectionId); if (!c) return;
            chartInstance = new Chart(c.getContext('2d'), { type: this.chartType, data: { labels: this.labels, datasets: ds }, options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: isC ? {} : { y: { beginAtZero: true } } } });
        },
        initChart() { this.$nextTick(() => this.updateChart()); },
        async saveChart() {
            const p = this.$el.closest('[x-data*="sectionManager"]');
            const sm = p ? Alpine.$data(p) : null; if (!sm) return;
            sm.sectionSaving = true;
            try {
                this.labels = this.labelsStr.split(',').map(l => l.trim()).filter(Boolean);
                const fd = new FormData(); fd.append('_method', 'PUT'); fd.append('name', sm.sectionName); fd.append('type', 'chart');
                fd.append('chart_type', this.chartType); fd.append('labels', JSON.stringify(this.labels)); fd.append('datasets', JSON.stringify(this.datasets));
                await ajaxRequest(ROUTES.dataUpdate(sm.sectionId), 'POST', fd, true);
                this.$dispatch('show-toast', {message: 'Graphique enregistré'});
            } catch (e) { this.$dispatch('show-toast', {message: e.message, type: 'error'}); } finally { sm.sectionSaving = false; }
        }
    }
}
</script>
@endpush
