@extends('layouts.app')

@section('content')
    <div
        x-data="answerManager({{ $question->id }})"
        class="relative"
    >
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">📝 Respostas: {{ $question->title }}</h1>
            <button @click="openModal()"
                    class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">
                ➕ Nova Resposta
            </button>
        </div>

        <table id="datatable-answers" class="stripe hover w-full text-sm text-white">
            <thead>
            <tr>
                <th>Letra</th>
                <th>Texto</th>
                <th>Correta</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($answers as $a)
                <tr data-id="{{ $a->id }}">
                    <td>{{ $a->label }}</td>
                    <td>{{ $a->text }}</td>
                    <td>
                    <span class="px-2 py-1 rounded {{ $a->is_correct ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $a->is_correct ? 'Sim' : 'Não' }}
                    </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button @click="editAnswer({{ $a->id }}, '{{ $a->label }}', `{{ $a->text }}`, {{ $a->is_correct ? 'true' : 'false' }})"
                                    class="px-3 py-1 bg-blue-500 rounded hover:bg-blue-600">
                                Editar
                            </button>
                            <button @click="deleteAnswer({{ $a->id }})"
                                    class="px-3 py-1 bg-red-600 rounded hover:bg-red-700">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Modal de Criação/Edição --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             x-cloak
             class="fixed inset-0 bg-black/60 flex justify-center items-center z-50">
            <div class="bg-slate-900 rounded-xl shadow-xl w-full max-w-lg p-6 relative">
                <button @click="closeModal()" class="absolute top-2 right-3 text-gray-400 hover:text-white">✖</button>
                <h2 class="text-xl font-bold mb-4" x-text="modalTitle"></h2>

                <form @submit.prevent="saveAnswer" class="space-y-4">
                    <div>
                        <label class="block text-sm mb-1">Letra</label>
                        <input type="text" x-model="form.label" maxlength="1"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.label" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Texto da resposta</label>
                        <textarea x-model="form.text"
                                  class="w-full p-2 rounded bg-slate-800 border border-slate-600"></textarea>
                        <p x-text="errors.text" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="form.is_correct" class="w-4 h-4 bg-slate-800">
                        <label>Marcar como correta</label>
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

            $('#datatable-answers').DataTable({
                responsive: true,
                pageLength: 10,
                language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json' },
                columnDefs: [{ orderable: false, targets: -1 }]
            })

            window.answerManager = (questionId) => ({
                showModal: false,
                modalTitle: '',
                editingId: null,
                questionId: questionId,
                form: { label: '', text: '', is_correct: false },
                errors: {},

                openModal() {
                    this.modalTitle = '➕ Nova Resposta'
                    this.showModal = true
                    this.editingId = null
                    this.form = { label: '', text: '', is_correct: false }
                    this.errors = {}
                },

                closeModal() {
                    this.showModal = false
                    this.editingId = null
                },

                editAnswer(id, label, text, is_correct) {
                    this.modalTitle = '✏️ Editar Resposta'
                    this.showModal = true
                    this.editingId = id
                    this.form.label = label
                    this.form.text = text
                    this.form.is_correct = is_correct
                    this.errors = {}
                },

                async saveAnswer() {
                    this.errors = {}
                    const payload = {
                        label: this.form.label,
                        text: this.form.text,
                        is_correct: this.form.is_correct ? 1 : 0
                    }
                    try {
                        if (this.editingId) {
                            await axios.put(`/admin/answers/${this.editingId}`, payload)
                        } else {
                            await axios.post(`/admin/questions/${this.questionId}/answers`, payload)
                        }
                        window.location.reload()
                    } catch (error) {
                        if (error.response?.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            alert('Erro ao salvar resposta')
                        }
                    }
                },

                async deleteAnswer(id) {
                    window.dispatchEvent(new CustomEvent('confirm', {
                        detail: {
                            message: 'Tem certeza que deseja excluir esta resposta?',
                            onConfirm: async () => {
                                try {
                                    await axios.delete(`/admin/answers/${id}`)
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Resposta excluída com sucesso!', type: 'success' } }))
                                    window.location.reload()
                                } catch (error) {
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Erro ao excluir resposta', type: 'error' } }))
                                }
                            }
                        }
                    }))
                }
            })
        </script>
    @endpush
@endsection
