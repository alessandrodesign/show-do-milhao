@extends('layouts.app')

@section('content')
    <div x-data="playerManager()" class="relative">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">👤 Jogadores</h1>
            <button @click="openModal()"
                    class="bg-amber-400 text-black font-semibold px-4 py-2 rounded-xl hover:brightness-110">
                ➕ Novo Jogador
            </button>
        </div>

        <table id="datatable-players" class="stripe hover w-full text-sm text-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>Apelido</th>
                <th>Email</th>
                <th>Score</th>
                <th>Melhor Prêmio</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($players as $p)
                <tr data-id="{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nickname }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->score }}</td>
                    <td>R$ {{ number_format($p->best_prize, 0, ',', '.') }}</td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button
                                @click="editPlayer({{ $p->id }}, '{{ addslashes($p->nickname) }}', '{{ $p->email }}', {{ $p->score }}, {{ $p->best_prize }})"
                                class="px-3 py-1 bg-blue-500 rounded hover:bg-blue-600">
                                Editar
                            </button>
                            <button @click="deletePlayer({{ $p->id }})"
                                    class="px-3 py-1 bg-red-600 rounded hover:bg-red-700">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Modal Jogador --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             x-cloak class="fixed inset-0 bg-black/60 flex justify-center items-center z-50">
            <div class="bg-slate-900 rounded-xl shadow-xl w-full max-w-lg p-6 relative">
                <button @click="closeModal()" class="absolute top-2 right-3 text-gray-400 hover:text-white">✖</button>
                <h2 class="text-xl font-bold mb-4" x-text="modalTitle"></h2>

                <form @submit.prevent="savePlayer" class="space-y-4">
                    <div>
                        <label class="block text-sm mb-1">Apelido</label>
                        <input type="text" x-model="form.nickname"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.nickname" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" x-model="form.email"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.email" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Score</label>
                        <input type="number" x-model="form.score"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.score" class="text-red-400 text-xs mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Melhor Prêmio (R$)</label>
                        <input type="number" x-model="form.best_prize"
                               class="w-full p-2 rounded bg-slate-800 border border-slate-600">
                        <p x-text="errors.best_prize" class="text-red-400 text-xs mt-1"></p>
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

            $('#datatable-players').DataTable({
                responsive: true,
                pageLength: 10,
                language: {url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'},
                columnDefs: [{orderable: false, targets: -1}]
            })

            window.playerManager = () => ({
                showModal: false,
                modalTitle: '',
                editingId: null,
                form: {nickname: '', email: '', score: 0, best_prize: 0},
                errors: {},

                openModal() {
                    this.modalTitle = '➕ Novo Jogador'
                    this.showModal = true
                    this.editingId = null
                    this.form = {nickname: '', email: '', score: 0, best_prize: 0}
                    this.errors = {}
                },

                closeModal() {
                    this.showModal = false
                    this.editingId = null
                },

                editPlayer(id, nickname, email, score, best_prize) {
                    this.modalTitle = '✏️ Editar Jogador'
                    this.showModal = true
                    this.editingId = id
                    this.form.nickname = nickname
                    this.form.email = email
                    this.form.score = score
                    this.form.best_prize = best_prize
                    this.errors = {}
                },

                async savePlayer() {
                    this.errors = {}
                    const payload = {
                        nickname: this.form.nickname,
                        email: this.form.email,
                        score: this.form.score,
                        best_prize: this.form.best_prize
                    }
                    try {
                        if (this.editingId) {
                            await axios.put(`/admin/players/${this.editingId}`, payload)
                        } else {
                            await axios.post(`/admin/players`, payload)
                        }
                        window.location.reload()
                    } catch (error) {
                        if (error.response?.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            alert('Erro ao salvar jogador')
                        }
                    }
                },

                async deletePlayer(id) {
                    if (!confirm('Tem certeza que deseja excluir este jogador?')) return
                    await axios.delete(`/admin/players/${id}`)
                    window.location.reload()
                }
            })
        </script>
    @endpush
@endsection
