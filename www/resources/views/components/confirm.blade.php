<div
    x-data="{
        show: false,
        message: '',
        confirmAction: null,
        open(msg, callback) {
            this.message = msg
            this.confirmAction = callback
            this.show = true
        },
        confirm() {
            if (this.confirmAction) this.confirmAction()
            this.show = false
        },
        cancel() {
            this.show = false
        }
    }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 bg-black/60 flex justify-center items-center z-50"
    @confirm.window="open($event.detail.message, $event.detail.onConfirm)"
>
    <div class="bg-slate-900 p-6 rounded-xl w-full max-w-md text-white shadow-xl relative">
        <p class="mb-6 text-lg font-semibold" x-text="message"></p>
        <div class="flex justify-end gap-4">
            <button @click="cancel(); playSound('click')" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-700">Cancelar</button>
            <button @click="confirm(); playSound('confirm')" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">Confirmar</button>
        </div>
    </div>
</div>
