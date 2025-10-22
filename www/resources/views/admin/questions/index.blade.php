@extends('layouts.app')

@section('content')
    <div x-data="questionManager()" class="relative">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">📚 Perguntas</h1>
            <button @click="openModal()"
                    class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">
                ➕ Nova Pergunta
            </button>
        </div>

        <table id="datatable-questions" class="stripe hover w-full text-sm text-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Dificuldade</th>
                <th>Respostas</th>
                <th>Ativa</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($questions as $q)
                <tr data-id="{{ $q->id }}">
                    <td>{{ $q->id }}</td>
                    <td>{{ $q->title }}</td>
                    <td>{{ ucfirst($q->difficulty) }}</td>
                    <td>{{ $q->answers_count }}</td>
                    <td>
                    <span class="px-2 py-1 rounded {{ $q->active ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $q->active ? 'Sim' : 'Não' }}
                    </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button
                                @click="editQuestion({{ $q->id }}, '{{ addslashes($q->title) }}', `{{ addslashes($q->statement) }}`, '{{ $q->difficulty }}', {{ $q->active ? 'true' : 'false' }})"
                                class="px-3 py-1 bg-blue-500 rounded hover:bg-blue-600">
                                Editar
                            </button>
                            <a href="{{ route('questions.answers.index', $q) }}"
                               class="px-3 py-1 bg-indigo-500 rounded hover:bg-indigo-600">
                                Respostas
                            </a>
                            <button @click="deleteQuestion({{ $q->id }})"
                                    class="px-3 py-1 bg-red-600 rounded hover:bg-red-700">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Modal --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             x-cloak class="fixed inset-0 bg-black/60 flex justify-center items-center z-50">
            <div class="bg-slate-900 rounded-xl shadow-xl w-full max-w-2xl p-6 relative">
                <button @click="closeModal()" class="absolute top-2 right-3 text-gray-400 hover:text-white">✖</button>
                <h2 class="text-xl font-bold mb-4" x-text="modalTitle"></h2>

                <form @submit.prevent="saveQuestion" class="space-y-4">
                    <div>
                        <label class="block text-sm mb-1">Título</label>
                        <input type="text" x-model="form.title"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.title" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Enunciado</label>
                        <textarea x-model="form.statement"
                                  class="w-full p-2 rounded bg-slate-800 border border-slate-600"></textarea>
                        <p x-text="errors.statement" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Dificuldade</label>
                        <select x-model="form.difficulty"
                                class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                            <option value="">Selecione</option>
                            <option value="easy">Fácil</option>
                            <option value="medium">Média</option>
                            <option value="hard">Difícil</option>
                            <option value="extreme">Extrema</option>
                        </select>
                        <p x-text="errors.difficulty" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="form.active" class="w-4 h-4 bg-slate-800">
                        <label>Ativar pergunta</label>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                                class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">
                            Salvar
                        </button>
                        <button type="button" @click="closeModal()"
                                class="px-4 py-2 bg-gray-600 rounded-xl hover:bg-gray-700">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import axios from 'axios'
            import $ from 'jquery'
            import 'datatables.net'
            import 'datatables.net-responsive'

            $('#datatable-questions').DataTable({
                responsive: true,
                pageLength: 10,
                language: {url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'},
                columnDefs: [{orderable: false, targets: -1}]
            })

            window.questionManager = () => ({
                showModal: false,
                modalTitle: '',
                editingId: null,
                form: {title: '', statement: '', difficulty: '', active: true},
                errors: {},

                openModal() {
                    this.modalTitle = '➕ Nova Pergunta'
                    this.showModal = true
                    this.editingId = null
                    this.form = { title: '', statement: '', difficulty: '', active: true }
                    this.errors = {}
                    playSound('modal-open')
                },

                closeModal() {
                    this.showModal = false
                    this.editingId = null
                },

                editQuestion(id, title, statement, difficulty, active) {
                    this.modalTitle = '✏️ Editar Pergunta'
                    this.showModal = true
                    this.editingId = id
                    this.form.title = title
                    this.form.statement = statement
                    this.form.difficulty = difficulty
                    this.form.active = active
                    this.errors = {}
                    playSound('modal-open')
                },

                async saveQuestion() {
                    this.errors = {}
                    const payload = {
                        title: this.form.title,
                        statement: this.form.statement,
                        difficulty: this.form.difficulty,
                        active: this.form.active ? 1 : 0
                    }
                    try {
                        if (this.editingId) {
                            await axios.put(`/admin/questions/${this.editingId}`, payload)
                        } else {
                            await axios.post(`/admin/questions`, payload)
                        }
                        window.location.reload()
                    } catch (error) {
                        if (error.response?.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            alert('Erro ao salvar pergunta')
                        }
                    }
                },

                async deleteQuestion(id) {
                    window.dispatchEvent(new CustomEvent('confirm', {
                        detail: {
                            message: 'Tem certeza que deseja excluir esta pergunta?',
                            onConfirm: async () => {
                                try {
                                    await axios.delete(`/admin/questions/${id}`)
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Pergunta excluída com sucesso!', type: 'success' } }))
                                    window.location.reload()
                                } catch (error) {
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Erro ao excluir pergunta', type: 'error' } }))
                                }
                            }
                        }
                    }))
                }
            })
        </script>
    @endpush
@endsection
